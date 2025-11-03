<?php

namespace App\Filament\Resources\Prescriptions;

use App\Filament\Resources\Prescriptions\Pages\ManagePrescriptions;
use App\Models\Prescription;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Notifications\Notification;


class PrescriptionResource extends Resource
{
    protected static ?string $model = Prescription::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'الوصفات الطبية';


    protected static ?string $modelLabel = 'الوصفة الطبية';
    protected static ?string $pluralModelLabel = 'الوصفات الطبية';
    protected static ?string $navigationLabel = 'الوصفات الطبية';
    
    
    
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('patient_id')
                    ->default(fn () => auth()->user()->id )
                    ->dehydrated(true)
                    ->required(),


                // القسم 1: معلومات الدواء
                Section::make('معلومات الدواء')
                    ->schema([
                        Textarea::make('medications')
                            ->required()
                            ->label('الأدوية الموصوفة')
                            ->default(null)
                            ->columnSpanFull(),

                        Textarea::make('instructions')
                            ->label('تعليمات الاستخدام')
                            ->default(null)
                            ->columnSpanFull(),

                        TextInput::make('frequency_per_day')
                            ->label('التكرار اليومي')
                            ->required()
                            ->default(null)
                            ->numeric(),
                    ])
                    ->columns(1),

                // القسم 2: الجرعة
                Section::make('تفاصيل الجرعة')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('quantity')
                                    ->label('الكمية للمرة الواحدة')
                                    // ->descriptions([
                                    //     'draft' => 'Is not visible.',
                                    //     'scheduled' => 'Will be visible.',
                                    //     'published' => 'Is visible.'
                                    // ])
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('dosage', $state && $get('unit') ? $state . ' ' . $get('unit') : null)
                                    )
                                    ->placeholder('أدخل الرقم'),

                                Select::make('unit')
                                    ->label('الوحدة')
                                    ->options([
                                        'حبة' => 'حبة',
                                        'ملي' => 'ملي',
                                        'إبرة' => 'إبرة',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, $set, $get) => 
                                        $set('dosage', $get('quantity') && $state ? $get('quantity') . ' ' . $state : null)
                                    ),
                            ]),

                        TextInput::make('dosage')
                            ->label('الجرعة الكاملة')
                            ->disabled()
                            ->reactive()
                            ->dehydrated(true)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                // القسم 3: مدة العلاج
                Section::make('مدة العلاج')
                    ->schema([
                        TextInput::make('duration_days')
                            ->label('مدة العلاج (بالأيام)')
                            ->required()
                            ->numeric()
                            ->default(null),

                        DatePicker::make('start_date')
                            ->label('تاريخ البدء')
                            ->required(),
                    ])
                    ->columns(2),

                // القسم 4: ملاحظات إضافية
                Section::make('ملاحظات')
                    ->schema([
                        Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->default(null)
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Prescription')
            ->columns([
            //    TextColumn::make('patient.name')
            //         ->label('تمت الإضافة بواسطة')
            //         ->formatStateUsing(function ($record) {
            //              dd($record->patient); 
            //           // 👈 سيعرض كل تفاصيل السجل عند تحميل الصفحة
            //             return $record->patient?->user->name ?? 'غير معروف';
                       
            //         }),


                TextColumn::make('frequency_per_day')
                    ->label('التكرار اليومي')
                    ->suffix('  مرات يوميًا')
                    ->searchable(),
                TextColumn::make('dosage')
                    ->label('الجرعة')
                    ->searchable(),
                TextColumn::make('duration_days')
                    ->label('مدة العلاج (بالأيام)')
                     ->suffix(' ايام/يوم')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('تاريخ البدء')
                    ->date()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->label('تاريخ الانتهاء')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                 //   ->enum(['active' => 'Active', 'completed' => 'Completed', 'dispensed' => 'Dispensed'])
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'active' => 'نشط',
                            'completed' => 'مكتمل',
                            'dispensed' => 'تم رفضها',
                            default => 'غير معروف',
                        };
                    })
                    ->colors([
                        'completed' => 'success', // أخضر
                        'active' => 'primary',  // أزرق
                        'dispensed' => 'danger',     // أحمر
                    ]),
                // TextColumn::make('notes')
                //     ->label('الملاحظات')
                //     ->searchable(),
                  
                // TextColumn::make('created_at')
                //     ->label('تاريخ الإنشاء')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->label('تاريخ التحديث')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => ManagePrescriptions::route('/'),
        ];
    }
    

}
