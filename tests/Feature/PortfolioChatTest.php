<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PortfolioChatTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.gemini.key', '');
    }

    #[TestWith(['Hi', 'Hi! How can I help you today? Have a project in mind, or are you looking for a UI/UX designer for your team?'])]
    #[TestWith(['Hello!', 'Hello! Are you exploring design support for a project, or looking for a UI/UX designer to join your team?'])]
    #[TestWith(['Hey', 'Hey! How can I help? I can share my design services, experience, or availability for your project or team.'])]
    #[TestWith(['Good morning', 'Good morning! How can I help? Are you planning a project or looking for a UI/UX designer for your team?'])]
    #[TestWith(['Good afternoon.', 'Good afternoon! Have a project in mind, or are you exploring UI/UX design support for your team?'])]
    #[TestWith(['Good evening!', 'Good evening! I can help with my services, experience, or how to get started on a project.'])]
    #[TestWith(['How are you?', 'Hey! I’m doing well, thanks for asking. Are you exploring design support for a project or looking for a UI/UX designer for your team?'])]
    public function test_it_replies_to_standalone_greetings(string $message, string $reply): void
    {
        $this->postJson('/api/portfolio-chat', ['message' => $message])
            ->assertOk()
            ->assertExactJson(['message' => $reply]);
    }

    #[TestWith(['What professional and project experience does Karl Justine have?'])]
    #[TestWith(['Tell me about Karl’s work experience.'])]
    #[TestWith(['What kind of experience does he have?'])]
    #[TestWith(['What has Karl worked on professionally?'])]
    public function test_it_gives_a_substantive_experience_overview_for_natural_question_variations(string $message): void
    {
        $response = $this->postJson('/api/portfolio-chat', ['message' => $message]);

        $response->assertOk();
        $answer = $response->json('message');
        $this->assertIsString($answer);
        $this->assertStringContainsString('Freelance UI/UX Designer', $answer);
        $this->assertStringContainsString('UI/UX Designer / Developer Intern', $answer);
        $this->assertStringContainsString('City Health Office 1', $answer);
        $this->assertStringContainsString('Queuing Management System', $answer);
        $this->assertStringContainsString('PHP, HTML, CSS, JavaScript, and MySQL', $answer);
        $this->assertStringContainsString('City Health Connect', $answer);
        $this->assertStringContainsString('Flutter', $answer);
        $this->assertStringContainsString('My experience spans freelance design', $answer);
        $this->assertStringContainsString('I designed workflows', $answer);
        $this->assertStringNotContainsString('His experience', $answer);
        $this->assertStringNotContainsString('Karl Justine', $answer);
        $this->assertStringContainsString("\n\nFreelance UI/UX Designer — Present\n-", $answer);
        $this->assertStringContainsString("\n\nUI/UX Designer / Developer Intern — 2026\n", $answer);
        $this->assertStringContainsString("\n\nUI/UX Designer / Programmer — Capstone, 2025–2026\n", $answer);
    }

    public function test_it_describes_documented_projects_when_asked_what_karl_has_worked_on(): void
    {
        $response = $this->postJson('/api/portfolio-chat', ['message' => 'What projects has Karl worked on?']);

        $response->assertOk();
        $answer = $response->json('message');
        $this->assertIsString($answer);
        $this->assertStringContainsString('Queuing Management System', $answer);
        $this->assertStringContainsString('City Health Connect', $answer);
        $this->assertStringContainsString('UI/UX Designer / Programmer', $answer);
        $this->assertStringContainsString('Flutter', $answer);
        $this->assertStringContainsString('I designed workflows', $answer);
        $this->assertStringContainsString('I contributed to development', $answer);
        $this->assertStringNotContainsString('He also', $answer);
        $this->assertStringContainsString("\n\nQueuing Management System — Internship, 2026\nMy role:", $answer);
        $this->assertStringContainsString("\n\nCity Health Connect — Capstone, 2025–2026\nMy role:", $answer);
    }

    public function test_it_instructs_gemini_to_format_answers_for_readability(): void
    {
        config()->set('services.gemini.key', 'test-api-key');
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => "A concise answer.\n\n- A relevant detail."],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $this->postJson('/api/portfolio-chat', ['message' => 'Can Karl help with a design project?'])
            ->assertOk()
            ->assertExactJson(['message' => "A concise answer.\n\n- A relevant detail."]);

        Http::assertSent(function (ClientRequest $request): bool {
            $prompt = $request['contents'][0]['parts'][0]['text'] ?? '';

            return str_contains($prompt, 'Format every response for readability.')
                && str_contains($prompt, 'short, descriptive plain-text headings')
                && str_contains($prompt, 'Keep responses as plain text.');
        });
    }

    public function test_it_explains_the_internship_role_and_contributions(): void
    {
        $response = $this->postJson('/api/portfolio-chat', ['message' => 'What did Karl do during his internship?']);

        $response->assertOk();
        $answer = $response->json('message');
        $this->assertIsString($answer);
        $this->assertStringContainsString('City Health Office 1', $answer);
        $this->assertStringContainsString('Queuing Management System', $answer);
        $this->assertStringContainsString('wireframes, layouts, and prototypes', $answer);
        $this->assertStringContainsString('PHP, HTML, CSS, JavaScript, and MySQL', $answer);
        $this->assertStringContainsString('I designed workflows', $answer);
        $this->assertStringContainsString('I developed and deployed', $answer);
    }

    public function test_it_explains_the_capstone_role_and_contributions(): void
    {
        $response = $this->postJson('/api/portfolio-chat', ['message' => 'Tell me about Karl’s City Health Connect capstone.']);

        $response->assertOk();
        $answer = $response->json('message');
        $this->assertIsString($answer);
        $this->assertStringContainsString('2025–2026', $answer);
        $this->assertStringContainsString('UI/UX Designer / Programmer', $answer);
        $this->assertStringContainsString('web and mobile interfaces', $answer);
        $this->assertStringContainsString('Flutter', $answer);
        $this->assertStringContainsString('MySQL', $answer);
        $this->assertStringContainsString('My capstone was City Health Connect', $answer);
        $this->assertStringContainsString('My role: UI/UX Designer / Programmer', $answer);
    }

    #[TestWith(['Is Karl Justine available for work?', 'Yes, I am currently available for full-time work.'])]
    #[TestWith(['Is Karl available to work?', 'Yes, I am currently available for full-time work.'])]
    #[TestWith(['Is Karl available for full-time work?', 'Yes, I am currently available for full-time work.'])]
    #[TestWith(['Is Karl available for freelance projects?', 'Yes, I am open for freelance projects.'])]
    #[TestWith(['Can I hire Karl for freelance projects?', 'Yes, I am open for freelance projects.'])]
    #[TestWith(['Is Karl available?', 'Yes, I am currently available for full-time work and am also open for freelance projects.'])]
    public function test_it_answers_availability_according_to_the_question(string $message, string $reply): void
    {
        $this->postJson('/api/portfolio-chat', ['message' => $message])
            ->assertOk()
            ->assertExactJson(['message' => $reply]);
    }

    public function test_it_gives_a_professional_background_summary(): void
    {
        $response = $this->postJson('/api/portfolio-chat', ['message' => 'Tell me about Karl Justine’s professional background']);

        $response->assertOk();
        $answer = $response->json('message');
        $this->assertIsString($answer);
        $this->assertStringContainsString('I am a UI/UX Designer', $answer);
        $this->assertStringContainsString('My experience spans freelance design', $answer);
        $this->assertStringNotContainsString('Karl Justine R. Membrere', $answer);
        $this->assertStringContainsString('Bachelor of Science in Information Technology', $answer);
        $this->assertStringContainsString('Urdaneta City University', $answer);
        $this->assertStringContainsString('Freelance UI/UX Designer', $answer);
        $this->assertStringContainsString('UI/UX Designer / Developer Intern', $answer);
        $this->assertStringContainsString('City Health Connect', $answer);
    }

    #[TestWith(['What did Karl study in college?', 'I completed a Bachelor of Science in Information Technology at Urdaneta City University in Urdaneta City, Pangasinan (2022–2026).'])]
    #[TestWith(['Where did he go to high school?', 'I completed Senior High School in the General Academic Strand (GAS) at Mataas Na Paaralang Juan C. Laya (MPJCL) (2020–2022).'])]
    #[TestWith(['What about junior high?', 'I completed Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL) (2016–2020).'])]
    #[TestWith(['What was his junior high school?', 'I completed Junior High School at Mataas Na Paaralang Juan C. Laya (MPJCL) (2016–2020).'])]
    public function test_it_limits_education_answers_to_the_requested_level(string $message, string $reply): void
    {
        $this->postJson('/api/portfolio-chat', ['message' => $message])
            ->assertOk()
            ->assertExactJson(['message' => $reply]);
    }

    public function test_it_returns_the_complete_education_history_when_requested(): void
    {
        $this->postJson('/api/portfolio-chat', ['message' => 'What is Karl’s complete educational background?'])
            ->assertOk()
            ->assertExactJson([
                'message' => "My education:\n- I completed a Bachelor of Science in Information Technology at Urdaneta City University (2022–2026).\n- I completed Senior High School in the General Academic Strand (GAS) at Mataas Na Paaralang Juan C. Laya (MPJCL) (2020–2022).\n- I completed Junior High School at MPJCL (2016–2020).",
            ]);
    }

    public function test_it_answers_skills_in_first_person_without_losing_documented_details(): void
    {
        $this->postJson('/api/portfolio-chat', ['message' => 'What are Karl Justine’s skills?'])
            ->assertOk()
            ->assertExactJson([
                'message' => 'My main tools and design skills focus on creating clear, usable interfaces. I use Figma and Affinity, and my design skills include UI/UX and visual design, wireframing, interactive prototyping, interaction design, design systems, mobile application design, web design, and usability. I use these skills to plan user flows, shape clear interfaces, and refine experiences around project requirements.',
            ]);
    }
}
