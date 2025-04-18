<?php

namespace App\Orchid\Screens\CoverLetter;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use App\Models\CoverLetter;

class CoverLetterEditScreen extends Screen
{
    /**
     * @var CoverLetter
     */
    public $coverLetter;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(CoverLetter $coverLetter): iterable
    {
        return [
            'coverLetter' => $coverLetter
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->coverLetter->exists ? 'Edit cover letter' : 'Creating a new cover letter';
    }

        /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "All cover letters";
    }


    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Create cover letter')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->coverLetter->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->coverLetter->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->coverLetter->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
			Layout::rows([
				Input::make('coverLetter.id')
					->hidden(),

				Input::make('coverLetter.name')
					->title('Name')
					->placeholder('Enter the cover letter name'),
		
				Input::make('coverLetter.company')
					->title('Company')
					->placeholder('Enter the company name')
					->help('Optional field'),
		
				Input::make('coverLetter.hiring_manager')
					->title('Hiring Manager')
					->placeholder('Enter the hiring manager name')
					->help('Optional field'),
		
				Quill::make('coverLetter.content')
					->title('Content')
					->placeholder('Enter the content of the cover letter'),
			])
        ];
    }

    /**
     * @param CoverLetter $coverLetter
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $coverLetter = CoverLetter::where('id', $request->get('coverLetter')['id'])->first() ?? new CoverLetter();

        $fields = $request->get('coverLetter');

        $coverLetter->fill($fields)->save();

        Alert::info('You have successfully created a cover letter.');

        return redirect()->route('platform.cover-letter.list');
    }

    /**
     * @param CoverLetter $coverLetter
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(CoverLetter $coverLetter)
    {
        $coverLetter->delete();

        Alert::info('You have successfully deleted the cover letter.');

        return redirect()->route('platform.cover-letter.list');
    }
}
