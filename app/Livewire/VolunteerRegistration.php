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
use Illuminate\Validation\ValidationException;

class VolunteerRegistration extends Component implements HasForms
{
    use InteractsWithForms;

    public $text;

    public $data = [];

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
        // $data = $this->form->getState();
        // $data['volunteer'] = 1;


        // $user = User::create($data);

    // Define validation rules
    $this->validate([
        'username'                  => 'required|unique:users,username|max:255',
        'email'                     => 'required|email|unique:users,email|max:255',
        'firstname'                 => 'required|max:255',
        'lastname'                  => 'required|max:255',
        'middle_name'               => 'nullable|max:255',
        'volunteer'                 => 'required|boolean',
        'birthday'                  => 'required|date',
        'is_company'                => 'required|boolean',
        'company_name'              => 'nullable|max:255',
        'company_address'           => 'nullable|max:255',
        'company_contact_number'    => 'nullable|phone:AUTO',
        'company_representative'    => 'nullable|max:255',
        'company_email'             => 'nullable|email|max:255',
        'school'                    => 'nullable|max:255',
        'school_address'            => 'nullable|max:255',
        'emergency_contact_name'    => 'nullable|max:255',
        'emergency_contact_number'  => 'nullable|phone:AUTO',
        'affiliate_type_id'         => 'required|exists:affiliate_types,id',
        'company_id'                => 'nullable|exists:companies,id',
        'program_id'                => 'nullable|exists:programs,id',
        'password'                  => 'required|confirmed|min:8', // Ensure password confirmation
    ]);
        $user = User::create([
            'username'                  => $this->username,
            'email'                     => $this->email,
            'firstname'                 => $this->firstname,
            'lastname'                  => $this->lastname,
            'middle_name'               => $this->middle_name,
            'volunteer'                 => $this->volunteer,
            'birthday'                  => $this->birthday,
            'is_company'                => $this->is_company,
            'company_name'              => $this->company_name,
            'company_address'           => $this->company_address,
            'company_contact_number'    => $this->company_contact_number,
            'company_representative'    => $this->company_representative,
            'company_email'             => $this->company_email,
            'school'                    => $this->school,
            'school_address'            => $this->school_address,
            'emergency_contact_name'    => $this->emergency_contact_name,
            'emergency_contact_number'  => $this->emergency_contact_number,
            'affiliate_type_id'         => $this->affiliate_type_id,
            'company_id'                => $this->company_id,
            'program_id'                => $this->program_id,
            'password'                  => bcrypt($this->password), // Secure the password
        ]);


        //dd($user);

        // //Temporary
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
            ->title('You have successfully registered.')
            ->success()
            ->send();

        // Reset form
        $this->form->fill();

        return redirect()->route('filament.admin.auth.login');
    }

    public function render()
    {
        return view('livewire.volunteer-registration');
    }
}

