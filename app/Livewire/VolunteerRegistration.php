<?php

namespace App\Livewire;

use App\Actions\VolunteerFields;
use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Program;
use App\Models\User;
use App\Models\Volunteer;
use Filament\DatePicker;
use Filament\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Grid;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class VolunteerRegistration extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $username;
    public $email;
    public $firstname;
    public $lastname;
    public $middle_name;
    public $volunteer;
    public $birthday;
    public $is_company;
    public $company_name;
    public $company_address;
    public $company_contact_number;
    public $company_representative;
    public $company_email;
    public $school;
    public $school_address;
    public $emergency_contact_name;
    public $emergency_contact_number;
    public $affiliate_type_id;
    public $company_id;
    public $program_id;
    public $password;
    public $passwordConfirmation;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Section::make()->schema((new VolunteerFields())->execute()),
            Section::make()
                ->schema([
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->revealable()
                        ->required(),
                    TextInput::make('passwordConfirmation')
                        ->password()
                        ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                        ->dehydrated(fn(?string $state): bool => filled($state))
                        ->revealable()
                        ->same('password')
                        ->required(),
                ])
                ->compact(),
        ]);
    }

    protected function getFormModel(): Model|string|null
    {
        return Volunteer::class;
    }

    public function mount()
    {
        $this->form->fill();
    }
    public function submit()
    {
        $data = $this->form->getState();
        $data['volunteer'] = 1;


        $user = User::create($data);

        //Temporary
        DB::table('users')
            ->where('id', $user->id)
        ->update(['email_verified_at' => now()]);

        $role = Role::where('name','volunteer')->first();

        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_id' => $user->id,
            'model_type' => 'App\Models\User',
        ]);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();

        // Reset form
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.volunteer-registration');
    }
}
