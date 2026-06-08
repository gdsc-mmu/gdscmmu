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
                'title' => 'The New Era of Agentic AI Breakthroughs',
                'description' => 'Researchers have unveiled a paradigm shift in AI, moving from simple chat interfaces to autonomous agents.',
                'category' => 'AI',
                'author' => 'GDGoC MMU AI/ML Team',
                'news_img' => json_encode(['https://mitsloan.mit.edu/sites/default/files/2026-02/agentic-ai-dobi.jpg' , 'https://341979.fs1.hubspotusercontent-eu1.net/hubfs/341979/openclaw1.png']),
                'paragraph' => json_encode([
                    'The latest advancements in Large Language Models (LLMs) have introduced the concept of "Agentic Workflows." Unlike traditional AI that simply responds to prompts, these new models can break down complex goals into smaller sub-tasks.',
                    'This leap is powered by a massive dataset refinement process that prioritizes high-quality reasoning over raw data volume. In practical terms, this means AI can now act as a digital collaborator that manages full software deployments or conducts deep-market research.',
                    'As we move closer to Artificial General Intelligence (AGI), the focus is shifting toward safe execution environments and ethical alignment to ensure these autonomous agents remain helpful and harmless.'
                ]),
                
                'date' => now(),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Scaling Microservices in 2026: Best Practices',
                'description' => 'A guide to modern software engineering, focusing on resilience and the developer experience.',
                'category' => 'Software Engineering',
                'author' => 'GDGoC MMU Software Engineering Team',
                'news_img' => json_encode(['https://vfunction.com/wp-content/uploads/2024/11/microservice-architecture-style.png' , 'https://www.gstatic.com/bricks/image/e03a1022-e613-4497-9d84-26850b24af72.jpg']),
                'paragraph' => json_encode([
                    'Modern software engineering has evolved far beyond just writing code; it is now about managing complexity at scale. The current industry standard emphasizes a "Shift-Left" approach, where testing and security are integrated early.',
                    'Implementing robust CI/CD pipelines ensures that code reviews are backed by automated quality gates, reducing the risk of production failures. Furthermore, the rise of Platform Engineering has enabled developers to use self-service portals.',
                    'By focusing on maintainable patterns, such as Domain-Driven Design (DDD) and asynchronous event-driven architectures, engineering teams can build systems that are resilient to cloud environment failures.'
                ]),
                'date' => now(),
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Zero Trust Architecture: Defending the Modern Perimeter',
                'description' => 'An in-depth look at why traditional firewalls are no longer enough in a decentralized world.',
                'category' => 'Cybersecurity',
                'author' => 'GDGoC MMU Cybersecurity Team',
                'news_img' => json_encode(['https://media.geeksforgeeks.org/wp-content/uploads/20240909155125/Steps-to-Design-a-Zero-Trust-System.png' , 'https://media.geeksforgeeks.org/wp-content/uploads/20250528175949819475/Zero-Trust-Architecture-in-Security-1.webp']),
                'paragraph' => json_encode([
                    'As the professional landscape shifts toward remote work and decentralized data, the traditional "Castle and Moat" security model has become obsolete. Today\'s threats require a Zero Trust Architecture (ZTA).',
                    'This framework operates on the principle of "least privilege," ensuring that users and devices are only granted the specific access they need. Multi-Factor Authentication (MFA) is now a fundamental requirement alongside behavioral analytics.',
                    'Protecting digital assets involves a multi-layered defense strategy, including encrypted data at rest and in transit, regular penetration testing, and a strong culture of security awareness among all employees.'
                ]),
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
