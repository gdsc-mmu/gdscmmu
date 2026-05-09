<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. AI & Machine Learning
        News::create([
            'title' => 'The New Era of Agentic AI Breakthroughs',
            'description' => 'Researchers have unveiled a paradigm shift in AI, moving from simple chat interfaces to autonomous agents.',
            'category' => 'AI',
            'author' => 'GDGoC MMU AI/ML Team',
            'paragraph' => [
                'The latest advancements in Large Language Models (LLMs) have introduced the concept of "Agentic Workflows." Unlike traditional AI that simply responds to prompts, these new models can break down complex goals into smaller sub-tasks.',
                'This leap is powered by a massive dataset refinement process that prioritizes high-quality reasoning over raw data volume. In practical terms, this means AI can now act as a digital collaborator that manages full software deployments or conducts deep-market research.',
                'As we move closer to Artificial General Intelligence (AGI), the focus is shifting toward safe execution environments and ethical alignment to ensure these autonomous agents remain helpful and harmless.'
            ],
            'date' => now(),
            'user_id' => 1, 
        ]);

        // 2. Software Engineering
        News::create([
            'title' => 'Scaling Microservices in 2026: Best Practices',
            'description' => 'A guide to modern software engineering, focusing on resilience and the developer experience.',
            'category' => 'Software Engineering',
            'author' => 'GDGoC MMU Software Engineering Team',
            'paragraph' => [
                'Modern software engineering has evolved far beyond just writing code; it is now about managing complexity at scale. The current industry standard emphasizes a "Shift-Left" approach, where testing and security are integrated early.',
                'Implementing robust CI/CD pipelines ensures that code reviews are backed by automated quality gates, reducing the risk of production failures. Furthermore, the rise of Platform Engineering has enabled developers to use self-service portals.',
                'By focusing on maintainable patterns, such as Domain-Driven Design (DDD) and asynchronous event-driven architectures, engineering teams can build systems that are resilient to cloud environment failures.'
            ],
            'date' => now(),
            'user_id' => 1,
        ]);

        // 3. Cybersecurity
        News::create([
            'title' => 'Zero Trust Architecture: Defending the Modern Perimeter',
            'description' => 'An in-depth look at why traditional firewalls are no longer enough in a decentralized world.',
            'category' => 'Cybersecurity',
            'author' => 'GDGoC MMU Cybersecurity Team',
            'paragraph' => [
                'As the professional landscape shifts toward remote work and decentralized data, the traditional "Castle and Moat" security model has become obsolete. Today’s threats require a Zero Trust Architecture (ZTA).',
                'This framework operates on the principle of "least privilege," ensuring that users and devices are only granted the specific access they need. Multi-Factor Authentication (MFA) is now a fundamental requirement alongside behavioral analytics.',
                'Protecting digital assets involves a multi-layered defense strategy, including encrypted data at rest and in transit, regular penetration testing, and a strong culture of security awareness among all employees.'
            ],
            'date' => now(),
            'user_id' => 1,
        ]);
    }
}