<?php

declare(strict_types=1);

use App\Orchid\Layouts\ProposalListLayout;
use App\Orchid\Layouts\ResumeListLayout;
use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Book\BookEditScreen;
use App\Orchid\Screens\Book\BookListScreen;
use App\Orchid\Screens\Project\ProjectEditScreen;
use App\Orchid\Screens\Project\ProjectListScreen;
use App\Orchid\Screens\CoverLetter\CoverLetterEditScreen;
use App\Orchid\Screens\CoverLetter\CoverLetterListScreen;
use App\Orchid\Screens\Resume\ResumeEditScreen;
use App\Orchid\Screens\Resume\ResumeListScreen;
use App\Orchid\Screens\Category\CategoryEditScreen;
use App\Orchid\Screens\Category\CategoryListScreen;
use App\Orchid\Screens\PageContent\PageContentEditScreen;
use App\Orchid\Screens\PageContent\PageContentListScreen;
use App\Orchid\Screens\Proposal\ProposalEditScreen;
use App\Orchid\Screens\Proposal\ProposalListScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Page Content
Route::screen('page-content/{pageContent?}', PageContentEditScreen::class)
->name('platform.page-content.edit');
Route::screen('page-contents', PageContentListScreen::class)
->name('platform.page-content.list');

// Categories
Route::screen('category/{category?}', CategoryEditScreen::class)
    ->name('platform.category.edit');
Route::screen('categories', CategoryListScreen::class)
    ->name('platform.category.list');

// Projects
Route::screen('project/{project?}', ProjectEditScreen::class)
    ->name('platform.project.edit');
Route::screen('projects', ProjectListScreen::class)
    ->name('platform.project.list');

// Proposals
Route::screen('proposal/{proposal?}', ProposalEditScreen::class)
	->name('platform.proposal.edit');
Route::screen('proposals', ProposalListScreen::class)
	->name('platform.proposal.list');
Route::get('proposal/duplicate/{proposal}', [ProposalListLayout::class, 'duplicate'])
	->name('platform.proposal.duplicate');

// Books
Route::screen('book/{book?}', BookEditScreen::class)
    ->name('platform.book.edit');
Route::screen('books', BookListScreen::class)
    ->name('platform.book.list');

// Cover letters
Route::screen('cover-letter/{coverLetter?}', CoverLetterEditScreen::class)
->name('platform.cover-letter.edit');
Route::screen('cover-letters', CoverLetterListScreen::class)
->name('platform.cover-letter.list');

// Resumes
Route::screen('resume/{resume?}', ResumeEditScreen::class)
    ->name('platform.resume.edit');
Route::get('resume/duplicate/{resume}', [ResumeListLayout::class, 'duplicate'])
	->name('platform.resume.duplicate');
Route::screen('resumes', ResumeListScreen::class)
    ->name('platform.resume.list');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/form/examples/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/form/examples/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/form/examples/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/form/examples/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/layout/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/charts/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/cards/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');


