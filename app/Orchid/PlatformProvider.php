<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Page Content')
                ->icon('bs.body-text')
                ->route('platform.page-content.list'),

			Menu::make('Books')
                ->icon('bs.book')
                ->route('platform.book.list'),

            Menu::make('Categories')
                ->icon('bs.tag')
                ->route('platform.category.list'),

            Menu::make('Projects')
                ->icon('bs.collection')
                ->route('platform.project.list'),

			Menu::make('Resumes')
                ->icon('bs.list')
                ->route('platform.resume.list'),

			Menu::make('Cover Letters')
                ->icon('bs.envelope')
                ->route('platform.cover-letter.list'),

			Menu::make('Proposals')
                ->icon('bs.briefcase')
                ->route('platform.proposal.list')
				->divider(),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank'),

            Menu::make('Changelog')
                ->icon('bs.box-arrow-up-right')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK)
                ->divider(),

            Menu::make('Back to Site')
                ->title('Actions')
                ->icon('bs.box-arrow-up-right')
                ->url('/'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
