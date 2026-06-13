<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Models\Survey;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostEventSurvey;
use League\Csv\Writer;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title')
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->columnSpan(2)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $event = \App\Models\Event::find($state);
                                    $set('title', $event->title . ' - Post Event Survey');
                                }
                            }),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1)
                            ->hidden()
                            ->disabled(fn ($context) => $context === 'create'),
                        ]),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpan('full'),
                    Forms\Components\Hidden::make('is_anonymous')
                        ->default(true),
                ])
                ->columns(2),

            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Repeater::make('questions')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('question')
                                        ->required()
                                        ->columnSpan(1),
                                    Forms\Components\Select::make('type')
                                        ->options([
                                            'text' => 'Text Response',
                                            'rating' => 'Rating (1-5)',
                                            'multiple_choice' => 'Multiple Choice',
                                        ])
                                        ->required()
                                        ->reactive()
                                        ->columnSpan(1),
                                ]),
                            Forms\Components\Repeater::make('options')
                                ->schema([
                                    Forms\Components\TextInput::make('option')
                                        ->required()
                                ])
                                ->visible(fn ($get) => $get('type') === 'multiple_choice')
                                ->label('Choice Options')
                                ->columns(1)
                                ->grid(2),
                        ])
                        ->defaultItems(1)
                        ->label('Survey Questions')
                        ->collapsible()
                        ->cloneable()
                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? null),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Opportunity')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responses_count')
                    ->counts('responses')
                    ->label('Responses'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                ->label('Send Surveys')
                ->icon('heroicon-o-envelope')
                ->action(function (Survey $survey) {
                    // Get registrations from the event
                    $event = \App\Models\Event::find($survey->event_id);
                    $registrations = $event->registrations;
                    foreach ($registrations as $registration) {

                        Mail::to($registration->volunteer->email)
                            ->send(new PostEventSurvey($survey, $registration));
                    }

                    Notification::make()
                        ->title('Surveys sent successfully')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Send Survey to All Registrants')
                ->modalDescription('Are you sure you want to send this survey to all event registrants?')
                ->modalSubmitActionLabel('Yes, send surveys'),
                Tables\Actions\Action::make('export')
                ->label('Export Responses')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function (Survey $record) {
                    // Create CSV
                    $csv = Writer::createFromString('');

                    // Add headers
                    $headers = ['Timestamp'];
                    if (!$record->is_anonymous) {
                        $headers[] = 'Respondent';
                        $headers[] = 'Email';
                    }
                    foreach ($record->questions as $question) {
                        $headers[] = $question['question'];
                    }
                    $csv->insertOne($headers);

                    // Add responses
                    foreach ($record->responses as $response) {
                        $row = [$response->created_at->format('Y-m-d H:i:s')];

                        if (!$record->is_anonymous) {
                            $row[] = $response->registration->name ?? 'N/A';
                            $row[] = $response->registration->email ?? 'N/A';
                        }

                        foreach ($response->answers as $answer) {
                            $row[] = $answer;
                        }

                        $csv->insertOne($row);
                    }

                    // Generate unique filename
                    $filename = "survey-responses-{$record->id}-" . date('Y-m-d-His') . '.csv';
                    $path = storage_path('app/tmp');

                    // Ensure directory exists
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    // Write CSV file directly
                    $fullPath = "{$path}/{$filename}";
                    file_put_contents($fullPath, $csv->toString());

                    // Verify file exists before download
                    if (!file_exists($fullPath)) {
                        throw new \Exception("Failed to create CSV file");
                    }

                    return response()->download(
                        $fullPath,
                        $filename,
                        ['Content-Type' => 'text/csv']
                    )->deleteFileAfterSend();
                }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveys::route('/'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }

    // Disable create
    public static function canCreate(): bool
    {
        return false;
    }
}
