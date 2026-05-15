<?php

namespace Tests\Feature;

use App\Filament\Resources\BookResource;
use App\Filament\Resources\BookResource\Pages\CreateBook;
use App\Filament\Resources\BookResource\Pages\EditBook;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CoverLetterResource;
use App\Filament\Resources\CoverLetterResource\Pages\CreateCoverLetter;
use App\Filament\Resources\CoverLetterResource\Pages\EditCoverLetter;
use App\Filament\Resources\PageContentResource;
use App\Filament\Resources\PageContentResource\Pages\EditPageContent;
use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Resources\ProposalResource;
use App\Filament\Resources\ProposalResource\Pages\CreateProposal;
use App\Filament\Resources\ProposalResource\Pages\EditProposal;
use App\Filament\Resources\ResumeResource;
use App\Filament\Resources\ResumeResource\Pages\CreateResume;
use App\Filament\Resources\ResumeResource\Pages\EditResume;
use App\Models\Book;
use App\Models\Category;
use App\Models\CoverLetter;
use App\Models\PageContent;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentAdminCrudTest extends TestCase
{
    use DatabaseMigrations;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'id' => 1,
            'email' => 'admin@example.com',
        ]);

        $this->actingAs($this->admin);
    }

    public function test_admin_resource_index_pages_load(): void
    {
        foreach (
            [
                PageContentResource::class,
                CategoryResource::class,
                BookResource::class,
                ProjectResource::class,
                ProposalResource::class,
                CoverLetterResource::class,
                ResumeResource::class,
            ]
            as $resource
        ) {
            $this->get($resource::getUrl('index'))->assertOk();
        }
    }

    public function test_page_content_can_be_updated_from_filament(): void
    {
        $pageContent = PageContent::create([
            'html_id' => 'hero',
            'name' => 'Hero Body',
            'key' => 'hero',
            'view' => 'Home',
            'content' => '<p>Old</p>',
            'properties' => [],
        ]);

        Livewire::test(EditPageContent::class, ['record' => $pageContent->getRouteKey()])
            ->fillForm([
                'name' => 'Hero Updated',
                'content' => '<p>Updated</p>',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(PageContent::class, [
            'id' => $pageContent->id,
            'name' => 'Hero Updated',
            'content' => '<p>Updated</p>',
        ]);
    }

    public function test_category_crud_works_from_filament(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => 'Philosophy',
                'type' => 'Book::class',
                'position' => 1,
                'active' => true,
                'properties' => ['html_selector' => 'philosophy'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::firstOrFail();
        $this->assertSame('philosophy', $category->selector);

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'name' => 'Economics',
                'type' => 'Book::class',
                'position' => 2,
                'active' => false,
                'properties' => ['html_selector' => 'economics'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $category->refresh();
        $this->assertSame('Economics', $category->name);
        $this->assertSame('economics', $category->selector);

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])->callAction(
            'delete',
        );

        $this->assertDatabaseMissing(Category::class, ['id' => $category->id]);
    }

    public function test_category_can_be_reordered_from_filament(): void
    {
        $first = Category::create([
            'name' => 'First',
            'type' => 'Book::class',
            'position' => 1,
            'properties' => ['html_selector' => 'first'],
            'active' => true,
        ]);

        $second = Category::create([
            'name' => 'Second',
            'type' => 'Book::class',
            'position' => 2,
            'properties' => ['html_selector' => 'second'],
            'active' => true,
        ]);

        Livewire::test(ListCategories::class)
            ->call('toggleTableReordering')
            ->call('reorderTable', [$second->getKey(), $first->getKey()])
            ->assertHasNoErrors();

        $this->assertSame(1, $second->refresh()->position);
        $this->assertSame(2, $first->refresh()->position);
    }

    public function test_book_crud_works_from_filament(): void
    {
        $category = Category::create([
            'name' => 'Books',
            'type' => 'Book::class',
            'position' => 1,
            'properties' => ['html_selector' => 'books'],
            'active' => true,
        ]);

        Livewire::test(CreateBook::class)
            ->fillForm([
                'category_id' => $category->id,
                'title' => 'Test Book',
                'author' => 'Alex',
                'subtitle' => 'Subtitle',
                'properties' => [
                    'image_name' => 'test_book',
                    'image_alt' => 'Test Book Cover',
                    'description' => 'Book preview',
                ],
                'external_link' => 'https://example.com/book',
                'external_image_link' => 'https://example.com/book.jpg',
                'position' => 1,
                'active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $book = Book::firstOrFail();
        $this->assertSame('test_book', $book->properties['image_name']);

        Livewire::test(EditBook::class, ['record' => $book->getRouteKey()])
            ->fillForm([
                'category_id' => $category->id,
                'title' => 'Updated Book',
                'author' => 'Updated Author',
                'subtitle' => 'Updated Subtitle',
                'properties' => [
                    'image_name' => 'updated_book',
                    'image_alt' => 'Updated Book Cover',
                    'description' => 'Updated preview',
                ],
                'external_link' => 'https://example.com/updated-book',
                'external_image_link' => 'https://example.com/updated-book.jpg',
                'position' => 2,
                'active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $book->refresh();
        $this->assertSame('Updated Book', $book->title);
        $this->assertSame('updated_book', $book->properties['image_name']);

        Livewire::test(EditBook::class, ['record' => $book->getRouteKey()])->callAction('delete');

        $this->assertDatabaseMissing(Book::class, ['id' => $book->id]);
    }

    public function test_project_crud_works_from_filament(): void
    {
        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Marketplacer',
                'position' => 1,
                'scope' => 'Professional',
                'external_link' => 'https://example.com/project',
                'external_image_link' => 'https://example.com/project.jpg',
                'properties' => ['image_name' => 'marketplacer'],
                'content' => [
                    'description' => '<p>Description</p>',
                    'excerpt' => 'Excerpt',
                    'bullets' => [['bullet' => 'Bullet one'], ['bullet' => 'Bullet two']],
                    'technology' => [['name' => 'Laravel'], ['name' => 'Vue']],
                ],
                'active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::firstOrFail();
        $this->assertSame(['Bullet one', 'Bullet two'], $project->content['bullets']);
        $this->assertSame(['Laravel', 'Vue'], $project->content['technology']);

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
            ->fillForm([
                'title' => 'Updated Project',
                'position' => 2,
                'scope' => 'Personal',
                'external_link' => 'https://example.com/updated-project',
                'external_image_link' => 'https://example.com/updated-project.jpg',
                'properties' => ['image_name' => 'updated-project'],
                'content' => [
                    'description' => '<p>Updated Description</p>',
                    'excerpt' => 'Updated excerpt',
                    'bullets' => [['bullet' => 'Updated bullet']],
                    'technology' => [['name' => 'PHP']],
                ],
                'active' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $project->refresh();
        $this->assertSame(['Updated bullet'], $project->content['bullets']);
        $this->assertSame(['PHP'], $project->content['technology']);

        Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])->callAction(
            'delete',
        );

        $this->assertDatabaseMissing(Project::class, ['id' => $project->id]);
    }

    public function test_proposal_crud_works_from_filament(): void
    {
        Livewire::test(CreateProposal::class)
            ->fillForm([
                'client' => 'Acme Co',
                'title' => 'Proposal Title',
                'completion_date' => '2026-01-01',
                'properties' => [
                    'use_client_agreement' => true,
                    'custom_css' => '#proposal .total { color: #0f172a; }',
                ],
                'content' => [
                    'description' => '<p>Description</p>',
                    'scope' => '<p>Scope</p>',
                    'technology' => '<p>Tech</p>',
                    'disclaimer' => '<p>Disclaimer</p>',
                    'payment_schedule' => [
                        [
                            'milestone' => 'Deposit',
                            'description' => 'Up front',
                            'amount_due' => 500,
                            'date' => '2026-01-05',
                        ],
                    ],
                ],
                'line_items' => [
                    ['description' => 'Line Item 1', 'price' => 500],
                    ['description' => 'Line Item 2', 'price' => 700],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $proposal = Proposal::firstOrFail();
        $this->assertSame(1200.0, (float) $proposal->total);
        $this->assertSame(
            '#proposal .total { color: #0f172a; }',
            $proposal->properties['custom_css'] ?? null,
        );

        Livewire::test(EditProposal::class, ['record' => $proposal->getRouteKey()])
            ->fillForm([
                'client' => 'Updated Client',
                'title' => 'Updated Proposal',
                'completion_date' => '2026-02-01',
                'properties' => [
                    'use_client_agreement' => false,
                    'custom_css' => '#proposal h1 { letter-spacing: 0.02em; }',
                ],
                'content' => [
                    'description' => '<p>Updated Description</p>',
                    'scope' => '<p>Updated Scope</p>',
                    'technology' => '<p>Updated Tech</p>',
                    'disclaimer' => '<p>Updated Disclaimer</p>',
                    'payment_schedule' => [
                        [
                            'milestone' => 'Final',
                            'description' => 'Completion',
                            'amount_due' => 900,
                            'date' => '2026-02-10',
                        ],
                    ],
                ],
                'line_items' => [['description' => 'Updated Line Item', 'price' => 900]],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $proposal->refresh();
        $this->assertSame(900.0, (float) $proposal->total);
        $this->assertSame('Updated Client', $proposal->client);
        $this->assertSame(
            '#proposal h1 { letter-spacing: 0.02em; }',
            $proposal->properties['custom_css'] ?? null,
        );

        Livewire::test(EditProposal::class, ['record' => $proposal->getRouteKey()])->callAction(
            'delete',
        );

        $this->assertDatabaseMissing(Proposal::class, ['id' => $proposal->id]);
    }

    public function test_cover_letter_crud_works_from_filament(): void
    {
        Livewire::test(CreateCoverLetter::class)
            ->fillForm([
                'name' => 'Backend Role',
                'company' => 'Acme',
                'hiring_manager' => 'Jane Doe',
                'content' => '<p>Hello</p>',
                'properties' => ['tone' => 'direct'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $coverLetter = CoverLetter::firstOrFail();
        $this->assertArrayHasKey('tone', $coverLetter->properties);

        Livewire::test(EditCoverLetter::class, ['record' => $coverLetter->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Backend Role',
                'company' => 'Updated Acme',
                'hiring_manager' => 'John Doe',
                'content' => '<p>Updated</p>',
                'properties' => ['tone' => 'warm'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $coverLetter->refresh();
        $this->assertSame('Updated Backend Role', $coverLetter->name);

        Livewire::test(EditCoverLetter::class, [
            'record' => $coverLetter->getRouteKey(),
        ])->callAction('delete');

        $this->assertDatabaseMissing(CoverLetter::class, ['id' => $coverLetter->id]);
    }

    public function test_resume_crud_works_from_filament(): void
    {
        $project = Project::create([
            'title' => 'Project Alpha',
            'scope' => 'Professional',
            'position' => 1,
            'content' => [
                'description' => '<p>Description</p>',
                'excerpt' => 'Excerpt',
                'technology' => ['Laravel'],
                'bullets' => ['Built feature'],
            ],
            'properties' => ['image_name' => 'project-alpha'],
            'active' => true,
        ]);

        Livewire::test(CreateResume::class)
            ->fillForm([
                'name' => 'Primary Resume',
                'bio' => 'Short bio',
                'projects' => [$project->id],
                'contacts' => [
                    [
                        'key' => 'email',
                        'href' => 'mailto:test@example.com',
                        'text' => 'test@example.com',
                    ],
                ],
                'references' => [
                    [
                        'name' => 'Jane Doe',
                        'title' => 'Manager',
                        'company' => 'Acme',
                        'location' => 'Remote',
                        'phone' => '555-1234',
                        'email' => 'jane@example.com',
                    ],
                ],
                'experience' => [
                    [
                        'title' => 'Developer',
                        'company' => 'Acme',
                        'location' => 'Remote',
                        'date' => '2024-2025',
                        'link' => 'https://example.com',
                        'stack' => 'Laravel',
                        'bullets' => [['bullet' => 'Built admin panel']],
                    ],
                ],
                'education' => null,
                'properties' => ['theme' => 'default'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $resume = Resume::firstOrFail();
        $this->assertSame([$project->id], $resume->projects()->pluck('projects.id')->all());
        $this->assertSame(['Built admin panel'], $resume->experience[0]['bullets']);

        Livewire::test(EditResume::class, ['record' => $resume->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Resume',
                'bio' => 'Updated bio',
                'projects' => [$project->id],
                'contacts' => [
                    ['key' => 'github', 'href' => 'https://github.com/alex', 'text' => 'alex'],
                ],
                'references' => [],
                'experience' => [
                    [
                        'title' => 'Senior Developer',
                        'company' => 'Acme',
                        'location' => 'Remote',
                        'date' => '2025-2026',
                        'link' => 'https://example.com/updated',
                        'stack' => 'PHP',
                        'bullets' => [['bullet' => 'Led migration']],
                    ],
                ],
                'education' => null,
                'properties' => ['theme' => 'clean'],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $resume->refresh();
        $this->assertSame('Updated Resume', $resume->name);
        $this->assertSame(['Led migration'], $resume->experience[0]['bullets']);

        Livewire::test(EditResume::class, ['record' => $resume->getRouteKey()])->callAction(
            'delete',
        );

        $this->assertDatabaseMissing(Resume::class, ['id' => $resume->id]);
    }
}
