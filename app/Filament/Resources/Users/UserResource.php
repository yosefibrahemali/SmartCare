<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

//protected static ?string $navigationIcon = 'heroicon-o-users'; // أيقونة للقائمة

    protected static ?string $navigationLabel = 'الأقارب';

    protected static ?string $modelLabel = 'شخص قريب';

    protected static ?string $pluralModelLabel = 'الأقارب';
    
    


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // إذا كان المستخدم الحالي من نوع patient، اعرض فقط الأقارب المرتبطين به
        if (auth()->check() && auth()->user()->role === 'patient') {
            $query->where('patient_id', auth()->user()->id)
                  ->where('role', 'relative'); // عرض الأقارب فقط
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'patient';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->label('الاسم الكامل')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->nullable(),
                // Select::make('role')
                //     ->label('الدور')
                //     ->options([
                //         // 'admin' => 'مدير',
                //         // 'patient' => 'مريض',
                //         'relative' => 'قريب',
                //     ])
                //     ->default('relative')
                //     ->placeholder('اختر الدور')
                //     ->searchable()      // لإمكانية البحث بين الخيارات
                //     ->preload()         // تحميل الخيارات مسبقًا لتسريع البحث
                //     ->required()
                //     ->columnSpanFull(),  // يأخذ العرض الكامل في الفورم
                //    // ->helperText('حدد الدور الخاص بالمستخدم'), // نص توضيحي

                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->placeholder('09********')
                    ->required()
                    ->tel()
                    ->default(''),
                Hidden::make('patient_id')
                    ->default(fn () => auth()->user()->id),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->type('password')
                    ->required(),
                TextInput::make('password_confirmation')
                    ->label('تأكيد كلمة المرور')
                    ->type('password')
                    ->password()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('User')
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم الكامل')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                
                    ->searchable(),
                TextColumn::make('role')


                    ->label('الدور')

                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'patient' => 'مريض',
                            'relative' => 'قريب',
                            'admin' => 'مسؤول',
                            default => 'غير معروف',
                        };
                    })
                    ->colors([
                        'relative' => 'success', // أخضر
                        'patient' => 'primary',  // أزرق
                        'admin' => 'danger',     // أحمر
                    ]),
                   
                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                TextColumn::make('patient.name') // استخدم اسم العلاقة بدل الحقل
                    ->label('المريض')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function getNavigation(): array
{
    return [
        NavigationGroup::make('الرئيسية')->items([
            NavigationItem::make('لوحة التحكم')
                ->url(route('filament.dashboard'))
                ->icon('heroicon-o-home')
                ->sort(1),

            NavigationItem::make('تذكيرات الأدوية')
                ->url(route('medications.reminders.index'))
                ->icon('heroicon-o-bell')
                ->sort(2),

            NavigationItem::make('الوصفات الطبية')
                ->url(route('medical.prescriptions.index'))
                ->icon('heroicon-o-document-text')
                ->sort(3),

            NavigationItem::make('الأقارب')
                ->url(route('relatives.index'))
                ->icon('heroicon-o-users')
                ->sort(4),
        ]),
    ];
}
   
}
