<?php

namespace App\Filament\Resources\MedicationReminders;

use App\Filament\Resources\MedicationReminders\Pages\ManageMedicationReminders;
use App\Models\MedicationReminder;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\BadgeColumn;


class MedicationReminderResource extends Resource
{
    protected static ?string $model = MedicationReminder::class;


    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';


    protected static ?string $recordTitleAttribute = 'تذكيرات الأدوية';

    protected static ?string $navigationLabel = 'تذكيرات الأدوية';

    protected static ?string $modelLabel = 'التذكير الدوائي';

    protected static ?string $pluralModelLabel = 'تذكيرات الأدوية';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('prescription.medications')
                    ->label('الأدوية الموصوفة'),
                
                TextInput::make('medication_name')
                    ->label('اسم الدواء')
                    ->required()
                    ->maxLength(255),   
                    
                Hidden::make('patient_id')
                    ->default(fn () => auth()->user()->id )
                    ->dehydrated(true),
                // TextInput::make('relative_id')
                //     ->numeric()
                //     ->default(null),
                DateTimePicker::make('notify_at')
                    ->label('وقت الإخطار')
                    ->required()
                    ->displayFormat('h:i A')  // صيغة 12 ساعة مع AM/PM
                    ->extraAttributes([
                        'dir' => 'rtl',
                    ])
                    ->format('Y-m-d H:i'),
                 //   t::make('status')
                //     ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'snoozed' => 'Snoozed'])
                //     ->default('pending')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('MedicationReminder')
            ->columns([
               
                // TextColumn::make('medication_name')
                //     ->label('الأدوية الموصوفة طبيًا')
                //     ->formatStateUsing(function ($record, $state) {
                //         dd($record);
                //         // نتحقق أولاً من وجود prescription
                //         if ($record->prescription && $record->prescription->medications->isNotEmpty()) {
                //             return $record->prescription->medications->pluck('medication_name')->implode(', ');
                //         }

                //         // إذا لم يكن هناك وصفة أو أدوية، نعرض القيمة الافتراضية
                //         return $state;
                //     })
                //     ->searchable()
                //     ->sortable(),

                    // TextColumn::make('medication_name')
                    // ->label('الأدوية الموصوفة طبيًا')
                    // ->formatStateUsing(function ($record, $state) {
                    //     dd($record);
                    //     // نتحقق أولاً من وجود prescription
                    //     if ($record->prescription && $record->prescription->medications->isNotEmpty()) {
                    //         return $record->prescription->medications->pluck('medication_name')->implode(', ');
                    //     }

                    //     // إذا لم يكن هناك وصفة أو أدوية، نعرض القيمة الافتراضية
                    //     return $state;
                    // })
                    // ->searchable()
                    // ->sortable(),

                // TextColumn::make('patient_id')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('relative_id')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('notify_at')
                    ->label('وقت الإخطار')
                    ->dateTime()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->label('الحالة')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'قيد الانتظار',
                            'confirmed' => 'تم التأكيد',
                            'snoozed' => 'تم التأجيل',
                            default => 'غير معروف',
                        };
                    })
                    ->colors([
                        'confirmed' => 'success', // أخضر
                        'pending'   => 'primary', // أزرق
                        'snoozed'   => 'danger',  // أحمر
                    ])
                    ->icon(function ($state) {
                        return match ($state) {
                            'pending' => 'heroicon-o-clock',     // ساعة
                            'confirmed' => 'heroicon-o-check',   // علامة صح
                            'snoozed' => 'heroicon-o-x',         // خط ×
                            default => 'heroicon-o-question-mark-circle', // علامة استفهام
                        };
                    })
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
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
            'index' => ManageMedicationReminders::route('/'),
        ];
    }
}
