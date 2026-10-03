<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortfolioChatController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($validated['message']);

        if ($message === '') {
            return response()->json([
                'message' => 'Please enter a portfolio-related question.',
            ], 400);
        }

        $apiKey = (string) config('services.gemini.key');
        if ($apiKey === '') {
            return response()->json([
                'message' => $this->fallbackPortfolioResponse($message),
            ]);
        }

        $portfolioContext = <<<'CONTEXT'
Karl Justine Membrere is a UI/UX designer and the owner of this portfolio. His full name is Karl Justine Membrere, and when referring to the designer, use "Karl Justine".

Profile and availability:
- UI/UX Designer and visual designer focused on interfaces and experiences
- Based in San Manuel, Pangasinan, Philippines
- Available for work and open for freelance opportunities
- Helps clients with web design, mobile app design, landing pages, dashboards, and product interfaces

Tools and workflow:
- Figma
- Affinity
- ChatGPT
- Gemini

Skills and work focus:
- UI/UX design
- Mobile application design
- Web design
- Dashboard design
- E-commerce design
- Design systems and prototyping
- User-centered product interfaces
- Landing page and portfolio designs

Education:
- Bachelor of Science in Information Technology, Urdaneta City University (2022–2026)
- Senior High School, General Academic Strand (GAS), MPJCL/region (2020–2022)

Portfolio projects:
- NOVA AI: An AI-powered mobile chat app where users can ask questions, get instant responses, solve problems, generate content, and explore ideas through intelligent AI conversations. Type: Mobile Application Design, AI / Chat Assistant.
- FLOWZA: A web-based dashboard platform that helps teams track active projects, monitor progress, manage tasks and deadlines, and stay updated on team activity. Type: Web Design, System Dashboard Design.
- Planty: A mobile plant e-commerce app where users can browse and purchase plants, manage their cart, and check out using card payment or cash on delivery. Type: Mobile Application Design, E-commerce.
- NORVA: A modern web e-commerce website where users can browse and shop for jackets and pants, view product details, add items to their cart, and manage their selected products. Type: Web Design, E-commerce.
- Car Rental: A mobile car rental app where users can explore available cars, discover vehicles on the map, save favorites, and easily book or rent a preferred car. Type: Mobile Application Design, Car Rental / Rental Service.
- Music Player: A music streaming web app where users can discover artists and trending songs, search genres, play and queue music, save favorites, and view friend listening activities. Type: Web Design, Music Streaming.

Experience:
- Freelance UI/UX Designer: Creates interfaces and experiences for clients and personal projects, including mobile apps, websites, and design services.
- Internship at City Health Office 1 (2026): Worked on a queuing management system for patient, doctor, and encoder queues.
- Capstone Project: City Health Connect, a multi-platform health services management system for the City Health Office of Urdaneta City.

Contact and portfolio info:
- Portfolio website: Karl Justine Membrere's portfolio
- Email: karljustinermembrere11272003@gmail.com
- Socials: LinkedIn, JobStreet, Instagram, Facebook
- Preferred inquiries: freelance, UI/UX design, web design, mobile app design, landing pages, dashboards, product design

Rules:
- Use only the portfolio context or user-provided details.
- Never invent projects, clients, tools, dates, awards, prices, education, or work history.
- If a user asks about a project, describe it using the project summaries above.
- If a user asks about tools, answer with the actual tools listed above and explain them in Karl Justine's design workflow.
- If the question is unrelated to Karl Justine's portfolio, politely explain that you can only help with portfolio-related questions.
- Keep answers concise, friendly, and professional.
CONTEXT;

        $systemPrompt = <<<PROMPT
You are the AI assistant for Karl Justine Membrere's portfolio.

Your job is to help visitors understand Karl Justine's work, projects, skills, tools, experience, education, freelancing availability, and portfolio information.

Use the portfolio context below as the source of truth.

Portfolio context:
{$portfolioContext}

Always refer to the designer as "Karl Justine".

When the user asks for a specific project, answer using the project descriptions above. For example:
- "What is Planty?" -> explain Planty as a plant e-commerce mobile app.
- "What is Nova AI?" -> explain Nova AI as an AI-powered mobile chat app.
- "What is FLOWZA?" -> explain FLOWZA as a web dashboard platform.
- "What tools does Karl Justine use?" -> answer with Figma, Affinity, ChatGPT, and Gemini in the context of his design workflow.
- "What projects has Karl Justine worked on?" -> list the portfolio projects in the project summaries.
- "Which projects are mobile applications?" -> include NOVA AI, Planty, and Car Rental.
- "Which projects are web-based?" -> include FLOWZA, NORVA, and Music Player.

Answer briefly but clearly, and avoid speculation.
PROMPT;

        $input = $systemPrompt."\n\nUser question:\n".$message;

        try {
            $response = Http::asJson()
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->timeout(20)
                ->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $input],
                            ],
                        ],
                    ],
                ]);

            if ($response->status() === 429) {
                return response()->json([
                    'message' => 'The assistant is temporarily busy. Please try again later.',
                ], 429);
            }

            if ($response->status() === 503) {
                return response()->json([
                    'message' => 'The AI service is temporarily unavailable. Please try again later.',
                ], 503);
            }

            if ($response->failed()) {
                Log::warning('Gemini portfolio chat request failed; using fallback response.', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);

                return response()->json([
                    'message' => $this->fallbackPortfolioResponse($message),
                ]);
            }

            $answer = $this->extractGeminiText($response->json());

            if ($answer === null || trim($answer) === '') {
                return response()->json([
                    'message' => $this->fallbackPortfolioResponse($message),
                ]);
            }

            return response()->json([
                'message' => $answer,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gemini portfolio chat exception.', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => $this->fallbackPortfolioResponse($message),
            ]);
        }
    }

    protected function fallbackPortfolioResponse(string $message): string
    {
        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $message) ?? $message));

        if (str_contains($normalized, 'project')) {
            if (str_contains($normalized, 'nova ai') || str_contains($normalized, 'nova')) {
                return 'NOVA AI is an AI-powered mobile chat app designed to help users ask questions, get instant answers, generate content, and explore ideas through intelligent conversations.';
            }

            if (str_contains($normalized, 'flowza')) {
                return 'FLOWZA is a web dashboard platform for tracking active projects, monitoring progress, managing tasks and deadlines, and staying updated on team activity.';
            }

            if (str_contains($normalized, 'planty')) {
                return 'Planty is a mobile plant e-commerce app where users can browse plants, manage their cart, and check out using card payment or cash on delivery.';
            }

            if (str_contains($normalized, 'norva')) {
                return 'NORVA is a modern web e-commerce website focused on jackets and pants, product details, cart management, and a smooth shopping experience.';
            }

            if (str_contains($normalized, 'car rental')) {
                return 'Car Rental is a mobile app for exploring available cars, viewing them on a map, saving favorites, and booking a preferred vehicle.';
            }

            if (str_contains($normalized, 'music player')) {
                return 'Music Player is a streaming web app for discovering artists, browsing music, playing queued songs, saving favorites, and seeing listening activity.';
            }

            return 'Karl Justine has worked on NOVA AI, FLOWZA, Planty, NORVA, Car Rental, and Music Player across mobile and web design projects.';
        }

        if (str_contains($normalized, 'service') || str_contains($normalized, 'help') || str_contains($normalized, 'offer')) {
            return 'Karl Justine offers UI/UX design, mobile app design, web design, landing pages, dashboards, e-commerce design, and product interface design for freelance and client work.';
        }

        if (str_contains($normalized, 'tool') || str_contains($normalized, 'software') || str_contains($normalized, 'figma') || str_contains($normalized, 'affinity') || str_contains($normalized, 'chatgpt') || str_contains($normalized, 'gemini')) {
            return 'Karl Justine uses Figma, Affinity, ChatGPT, and Gemini in his design workflow for prototyping, exploration, and product design work.';
        }

        if (str_contains($normalized, 'avail') || str_contains($normalized, 'work') || str_contains($normalized, 'freelance') || str_contains($normalized, 'hire')) {
            return 'Yes. Karl Justine is available for work and open to freelance opportunities across UI/UX, web, and mobile interface design.';
        }

        if (str_contains($normalized, 'experience') || str_contains($normalized, 'education')) {
            return 'Karl Justine is a UI/UX designer based in San Manuel, Pangasinan, and is currently pursuing a Bachelor of Science in Information Technology at Urdaneta City University.';
        }

        if (str_contains($normalized, 'unrelated') || str_contains($normalized, 'other')) {
            return 'I can only help with portfolio-related questions about Karl Justine’s work, projects, skills, tools, experience, and availability.';
        }

        return 'I can help with Karl Justine’s portfolio projects, services, tools, experience, education, and availability. Ask me about his work or design projects.';
    }

    protected function extractGeminiText(array $payload): ?string
    {
        foreach ($payload['candidates'] ?? [] as $candidate) {
            $parts = $candidate['content']['parts'] ?? [];
            foreach ($parts as $part) {
                if (isset($part['text']) && trim((string) $part['text']) !== '') {
                    return trim((string) $part['text']);
                }
            }
        }

        return null;
    }
}
