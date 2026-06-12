<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
       DB::table('news')->insert([
            [
                'title' => 'Anthropic Launches Claude Fable 5, Bringing Next-Gen "Mythos" Intelligence to the Public with Ultra-Cautious Safeguards',
                'description' => 'Anthropic introduces Claude Fable 5, a Mythos-class AI model capable of executing months of complex, autonomous engineering and scientific research in days.',
                'category' => 'AI',
                'author' => 'GDGoC MMU Team',
                'news_img' => json_encode(['https://www.geeky-gadgets.com/wp-content/uploads/2026/06/anthropic-mythos-launch-e1781036308398.webp','https://cdn.sanity.io/images/n7k93mg6/ecorpitdb/c84893e1cf47c448d012bbe7ca155d28aa158415-1729x910.png?w=1200&fm=jpg&q=80&fit=max&bg=fff']),
                    
                'paragraph' => json_encode([
                    "Anthropic has officially launched Claude Fable 5, introducing its new \"Mythos-class\" AI tier to the general public as the company’s most intelligent model to date. This release highlights a major advancement in Large Language Models (LLMs) with the concept of \"Agentic Workflows.\" Unlike traditional AI that simply responds to short prompts, Fable 5 can work autonomously over vast stretches of time and data to break down complex goals into smaller sub-tasks. However, because these frontier capabilities present significant risks of misuse in areas like cybersecurity, Anthropic is deploying the model behind a hyper-conservative safety architecture to ensure autonomous execution remains safe and aligned." ,

                    "This technological leap is powered by a massive dataset refinement process that prioritizes high-quality, complex reasoning over raw data volume. In practical terms, this means the AI can now act as a true digital collaborator capable of managing full software deployments or conducting deep analytical knowledge work. In early testing with Stripe, Fable 5 compressed months of work into days by migrating a 50-million-line Ruby codebase in a single day. It also set new performance records on Cognition’s FrontierCode evaluation, Hebbia’s Finance Benchmark, and trading-analysis tests by IMC, all while slashing input and output token costs by more than half compared to previous preview models." ,

                    "Fable 5 also marks a paradigm shift in pure vision and memory capacity, allowing it to extract precise data from complex scientific figures or rebuild web applications entirely from screenshots. Proving its ability to execute tasks with minimal scaffolding, the model successfully defeated Pokémon FireRed using a raw, vision-only harness without any backend map aids. Furthermore, its long-context focus is amplified by persistent file-based memory, allowing it to improve its own outputs using its own notes. When playing the game Slay the Spire, this advanced memory access boosted Fable 5's performance three times more than older models like Claude Opus 4.8." ,

                    "To safely manage these dual-use capabilities, Anthropic has implemented a dynamic fallback system driven by separate AI safety classifiers. If a user prompts the model with a request flagged as highly sensitive regarding offensive cybersecurity, advanced biology/chemistry, or distillation attempts, the system automatically routes the query to Claude Opus 4.8 instead of issuing a flat refusal. These cautious filters trigger in less than 5% of average user sessions. Extensive red-teaming and automated jailbreak evaluations spanning 400 turns proved the robustness of these classifiers, resulting in zero compliance with harmful requests." ,

                    "As the industry moves closer to Artificial General Intelligence (AGI), the focus is shifting toward trusted execution environments and strict data privacy. For verified researchers and government partners under Project Glasswing, Anthropic is offering Claude Mythos 5, which lifts these safeguards to accelerate breakthrough genomics research and ten-fold drug design workflows. To protect these systems from novel exploits over time, a mandatory 30-day data retention policy has been instituted for all Mythos-class traffic. This data will never be used for commercial training, serving solely to refine safety metrics, minimize false positives, and ensure these autonomous agents remain helpful and harmless."
                ]),
                'news_source' => 'https://www.anthropic.com/news/claude-fable-5-mythos-5',


                
                'date' => now(),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
    }

    public function down(): void
    {
        DB::table('news')->truncate();
    }

    
};
