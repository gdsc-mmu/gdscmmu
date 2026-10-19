#!/usr/bin/env python3
"""
Generate Developer Release Notes for new versions.
Parses git history between releases, categorizes features, bug fixes,
and improvements, and detects project-specific action items (migrations, dependencies).
"""

import argparse
import os
import re
import subprocess
import sys
from datetime import datetime

RECORD_SEP = "\x1e"
FIELD_SEP = "\x1f"

def run_git(cmd, cwd=None):
    try:
        res = subprocess.run(
            cmd,
            cwd=cwd,
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            encoding="utf-8",
            check=True
        )
        return res.stdout.strip()
    except subprocess.CalledProcessError as e:
        return ""

def ref_exists(ref):
    res = run_git(["git", "rev-parse", "--verify", "--quiet", ref])
    return bool(res)

def get_initial_commit():
    return run_git(["git", "rev-list", "--max-parents=0", "HEAD"]).splitlines()[-1]

def get_previous_tag(target_commit):
    # Try finding the previous tag prior to target_commit
    prev = run_git(["git", "describe", "--tags", "--abbrev=0", f"{target_commit}^"])
    if prev:
        return prev
    
    # Alternatively check sorted tags
    tags_out = run_git(["git", "tag", "--sort=-creatordate"])
    tags = [t.strip() for t in tags_out.splitlines() if t.strip()]
    if target_commit in tags:
        idx = tags.index(target_commit)
        if idx + 1 < len(tags):
            return tags[idx + 1]
    elif tags:
        return tags[0]

    return None

def get_commits(range_spec):
    fmt = f"%H{FIELD_SEP}%h{FIELD_SEP}%s{FIELD_SEP}%an{FIELD_SEP}%ae{FIELD_SEP}%ad{RECORD_SEP}"
    args = ["git", "log", f"--format={fmt}", "--date=short"]
    if range_spec:
        args.append(range_spec)
    out = run_git(args)
    commits = []
    for raw in out.split(RECORD_SEP):
        raw = raw.strip()
        if not raw:
            continue
        parts = raw.split(FIELD_SEP)
        if len(parts) >= 6:
            commits.append({
                "hash": parts[0],
                "short_hash": parts[1],
                "subject": parts[2],
                "author": parts[3],
                "email": parts[4],
                "date": parts[5]
            })
    return commits

def get_changed_files(range_spec, target_commit):
    if range_spec and ".." in range_spec:
        out = run_git(["git", "diff", "--name-only", range_spec])
    else:
        out = run_git(["git", "ls-tree", "-r", "--name-only", target_commit])
    return [f.strip() for f in out.splitlines() if f.strip()]

def categorize_commit(subject):
    s = subject.strip()
    
    # Feature detection
    if re.search(r"^(feat|feature)(\([^)]+\))?:\s*", s, re.IGNORECASE) or \
       re.search(r"^(add|added|adds)\b", s, re.IGNORECASE) or \
       re.search(r"feature/|feat/", s, re.IGNORECASE):
        # Clean title if conventional commit
        clean_title = re.sub(r"^(feat|feature)(\([^)]+\))?:\s*", "", s, flags=re.IGNORECASE)
        # Extract scope if any
        m = re.match(r"^(feat|feature)\(([^)]+)\):\s*", s, re.IGNORECASE)
        scope = m.group(2) if m else None
        return "features", clean_title, scope

    # Bug fix detection
    if re.search(r"^(fix|fixed|fixes|bugfix|bug|hotfix|patch)(\([^)]+\))?:\s*", s, re.IGNORECASE) or \
       re.search(r"^(fix|fixed)\b", s, re.IGNORECASE):
        clean_title = re.sub(r"^(fix|fixed|fixes|bugfix|bug|hotfix|patch)(\([^)]+\))?:\s*", "", s, flags=re.IGNORECASE)
        m = re.match(r"^(fix|bugfix|hotfix)\(([^)]+)\):\s*", s, re.IGNORECASE)
        scope = m.group(2) if m else None
        return "fixes", clean_title, scope

    # Refactor / Performance
    if re.search(r"^(refactor|perf|style|clean|cleanup)(\([^)]+\))?:\s*", s, re.IGNORECASE):
        clean_title = re.sub(r"^(refactor|perf|style|clean|cleanup)(\([^)]+\))?:\s*", "", s, flags=re.IGNORECASE)
        return "improvements", clean_title, None

    # Docs
    if re.search(r"^(docs?|documentation)(\([^)]+\))?:\s*", s, re.IGNORECASE):
        clean_title = re.sub(r"^(docs?|documentation)(\([^)]+\))?:\s*", "", s, flags=re.IGNORECASE)
        return "docs", clean_title, None

    # Maintenance / Chore / CI
    if re.search(r"^(chore|test|tests|ci|build|workflow)(\([^)]+\))?:\s*", s, re.IGNORECASE):
        clean_title = re.sub(r"^(chore|test|tests|ci|build|workflow)(\([^)]+\))?:\s*", "", s, flags=re.IGNORECASE)
        return "maintenance", clean_title, None

    return "others", s, None

def format_commit_item(item, clean_title, scope, repo_url=None):
    short_hash = item["short_hash"]
    commit_link = f"[`{short_hash}`]({repo_url}/commit/{item['hash']})" if repo_url else f"`{short_hash}`"
    scope_str = f"**{scope}**: " if scope else ""
    return f"- {scope_str}{clean_title} ({commit_link} by @{item['author']})"

def generate_notes(version_tag, prev_ref=None, repo=None, output_path=None):
    # Determine the actual commit for version_tag
    target_commit = version_tag if ref_exists(version_tag) else "HEAD"

    if not prev_ref:
        prev_ref = get_previous_tag(target_commit)
    
    # Check if range is valid
    if not prev_ref or prev_ref == target_commit:
        range_spec = target_commit
        display_range = f"Initial Release -> `{version_tag}`"
        diff_range = None
    else:
        range_spec = f"{prev_ref}..{target_commit}"
        display_range = f"`{prev_ref}`...`{version_tag}`"
        diff_range = range_spec

    commits = get_commits(range_spec)
    changed_files = get_changed_files(diff_range, target_commit)

    repo_url = f"https://github.com/{repo}" if repo else None

    # Buckets
    categories = {
        "features": [],
        "fixes": [],
        "improvements": [],
        "docs": [],
        "maintenance": [],
        "others": []
    }

    authors = set()

    for c in commits:
        authors.add(c["author"])
        cat, clean_title, scope = categorize_commit(c["subject"])
        categories[cat].append((c, clean_title, scope))

    # Detect Developer Action Items
    action_items = []
    if any(f.startswith("database/migrations") for f in changed_files):
        action_items.append("🗄️ **Database Migration**: New schema changes detected. Run `php artisan migrate` after updating.")
    if any(f in ("composer.json", "composer.lock") for f in changed_files):
        action_items.append("📦 **PHP Dependencies**: Dependencies were updated. Run `composer install`.")
    if any(f in ("package.json", "package-lock.json", "vite.config.js") for f in changed_files):
        action_items.append("🎨 **Frontend Assets**: Node dependencies or build config changed. Run `npm install && npm run build`.")
    if any(f == ".env.example" for f in changed_files):
        action_items.append("⚙️ **Environment Variables**: `.env.example` has updates. Check and update your local `.env` file.")

    # Build Markdown
    release_date = datetime.now().strftime("%Y-%m-%d")
    lines = []
    lines.append(f"# 🚀 Developer Release Notes - {version_tag}")
    lines.append("")
    lines.append(f"> **Release Date:** {release_date}  ")
    lines.append(f"> **Commit Range:** {display_range}  ")
    lines.append(f"> **Total Commits:** {len(commits)}  ")
    lines.append("")

    if action_items:
        lines.append("## ⚠️ Developer Action Required")
        for act in action_items:
            lines.append(f"- {act}")
        lines.append("")

    # Features Section (Primary focus requested by user)
    lines.append("## ✨ Features Added")
    if categories["features"]:
        for item, clean_title, scope in categories["features"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
    else:
        lines.append("_No new features introduced in this release._")
    lines.append("")

    # Bug Fixes Section
    if categories["fixes"]:
        lines.append("## 🐛 Bug Fixes & Patches")
        for item, clean_title, scope in categories["fixes"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
        lines.append("")

    # Improvements Section
    if categories["improvements"]:
        lines.append("## 🛠️ Enhancements & Refactoring")
        for item, clean_title, scope in categories["improvements"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
        lines.append("")

    # Docs & Maintenance
    if categories["docs"] or categories["maintenance"]:
        lines.append("## 📚 Documentation & Maintenance")
        for item, clean_title, scope in categories["docs"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
        for item, clean_title, scope in categories["maintenance"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
        lines.append("")

    # Other changes
    if categories["others"]:
        lines.append("## 🔍 Other Changes")
        for item, clean_title, scope in categories["others"]:
            lines.append(format_commit_item(item, clean_title, scope, repo_url))
        lines.append("")

    # Contributors
    if authors:
        lines.append("## 👥 Contributors")
        lines.append(", ".join([f"**{a}**" for a in sorted(authors)]))
        lines.append("")

    lines.append("---")
    lines.append("*Automated developer note generated by GitHub Actions.*")

    markdown_content = "\n".join(lines)

    if output_path:
        with open(output_path, "w", encoding="utf-8") as f:
            f.write(markdown_content)

    return markdown_content

def main():
    parser = argparse.ArgumentParser(description="Generate Developer Release Notes")
    parser.add_argument("--tag", default="HEAD", help="Current version tag or commit ref (default: HEAD)")
    parser.add_argument("--prev-tag", default=None, help="Previous version tag or commit ref")
    parser.add_argument("--repo", default=os.getenv("GITHUB_REPOSITORY", "ronylee11/gdscmmu"), help="GitHub repository (owner/repo)")
    parser.add_argument("--output", default="DEVELOPER_NOTES.md", help="Output markdown file path")

    args = parser.parse_args()

    content = generate_notes(
        version_tag=args.tag,
        prev_ref=args.prev_tag,
        repo=args.repo,
        output_path=args.output
    )

    print(f"Developer notes generated successfully at: {args.output}")

if __name__ == "__main__":
    main()
