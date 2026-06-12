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
                'news_img' => json_encode(['https://www.geeky-gadgets.com/wp-content/uploads/2026/06/anthropic-mythos-launch-e1781036308398.webp', 'https://substackcdn.com/image/fetch/$s_!yu9B!,f_auto,q_auto:good,fl_progressive:steep/https%3A%2F%2Fsubstack-post-media.s3.amazonaws.com%2Fpublic%2Fimages%2F518ba166-f6dd-4220-b84c-c737b4027255_1456x816.jpeg']),
                    
                'paragraph' => json_encode([
                    "Anthropic has officially launched Claude Fable 5, introducing its new \"Mythos-class\" AI tier to the general public as the company’s most intelligent model to date. This release highlights a major advancement in Large Language Models (LLMs) with the concept of \"Agentic Workflows.\" Unlike traditional AI that simply responds to short prompts, Fable 5 can work autonomously over vast stretches of time and data to break down complex goals into smaller sub-tasks. According to the benchmark data in table below, this flagship model establishes definitive industry dominance by securing the top score in 12 out of 14 evaluated categories against leading models like GPT 5.5 and Gemini 3.1 Pro.",

                    "This technological leap is powered by a massive dataset refinement process that prioritizes high-quality, complex reasoning over raw data volume. In practical terms, this means the AI can now act as a true digital collaborator capable of managing full software deployments or conducting deep analytical knowledge work. In early testing with Stripe, Fable 5 compressed months of work into days by migrating a 50-million-line Ruby codebase in a single day. It also set massive performance records in agentic coding, where its 29.3% score on the rigorous FrontierCode (Diamond) test shown in image below more than doubles the performance of Claude Opus 4.8 (13.4%) and vastly outpaces GPT 5.5 (5.7%).",

                    "Fable 5 also marks a paradigm shift in pure vision and memory capacity, allowing it to extract precise data from complex scientific figures or rebuild web applications entirely from screenshots. Proving its ability to execute tasks with minimal scaffolding, the model successfully defeated Pokémon FireRed using a raw, vision-only harness without any backend map aids. Furthermore, its long-context focus is amplified by persistent file-based memory, allowing it to improve its own outputs using its own notes. This deep spatial and conceptual logic is backed up by image above, where the model secures unmatched industry-leading marks in domain-specific reasoning benchmarks like Spatial Reasoning (38.6%) and Legal Agent tasks (13.3%).",

                    "To safely manage these dual-use capabilities, Anthropic has implemented a dynamic fallback system driven by separate AI safety classifiers. If a user prompts the model with a request flagged as highly sensitive regarding offensive cybersecurity, advanced biology/chemistry, or distillation attempts, the system automatically routes the query to Claude Opus 4.8 instead of issuing a flat refusal. These cautious filters trigger in less than 5% of average user sessions. The sheer defensive capability of the unthrottled Mythos architecture is demonstrated in image above, which shows a massive breakthrough in offensive cybersecurity, scoring 78.0% on ExploitBench (Cap%) compared to Opus 4.8’s 40.0% and GPT 5.5’s 34.0%.",

                    "As the industry moves closer to Artificial General Intelligence (AGI), the focus is shifting toward trusted execution environments like Project Glasswing and strict data privacy, including a mandatory 30-day data retention policy. For verified researchers, Anthropic is offering Claude Mythos 5 to accelerate breakthrough genomics research and ten-fold drug design workflows, highlighted by a top Biology score of 46.1% on BioMysteryBench in the table above. However, due to conservative public safety classifiers, users should note that the publicly available Claude Fable 5 will automatically fall back to Claude Opus 4.8 on sensitive cybersecurity and biology queries, bringing its real-world public performance on those specific starred tasks closer to Opus 4.8's scores."
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
