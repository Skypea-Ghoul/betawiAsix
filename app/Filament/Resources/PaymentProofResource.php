<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentProofResource\Pages;
use App\Filament\Resources\PaymentProofResource\RelationManagers;
use App\Models\PaymentProof;
use App\Models\PaymentProofs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
// use Filament\Tables\Columns\DateTimeColumn;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentProofResource extends Resource
{
    protected static ?string $model = PaymentProofs::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
                        ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('order_id')
                                    ->label('Order')
                                    ->relationship('order', 'id')
                                    ->searchable()
                                    ->required(),

                                Select::make('user_id')
                                    ->label('User')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->required(),

                                TextInput::make('amount')
                                    ->label('Jumlah Bayar')
                                    ->numeric()
                                    ->placeholder('0.00')
                                    ->required(),

                                FileUpload::make('proof_path')
                                    ->label('Bukti Pembayaran')
                                    ->directory('payment_proofs')
                                    ->image()
                                    ->maxSize(1024)
                                    ->required(),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Select::make('verified_by')
                                    ->label('Diverifikasi Oleh')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->nullable(),

                                DateTimePicker::make('verified_at')
                                    ->label('Waktu Verifikasi')
                                    ->nullable(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  TextColumn::make('id')->label('ID')->sortable()->searchable(),
                TextColumn::make('order.id')
                    ->label('Order')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
         TextColumn::make('proof_path')
    ->label('Bukti')
    ->formatStateUsing(function ($state) {
        $paths = json_decode($state, true);
        if (is_array($paths)) {
            return collect($paths)->map(function($img) {
                $url = asset('storage/'.$img);
                return '
                    <div style="display:inline-block;margin-right:12px;text-align:center;">
                        <a href="'.$url.'" target="_blank">
                            <img src="'.$url.'" style="width:60px;height:60px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px #0001;margin-bottom:4px;transform: translateX(10px);">
                        </a>
                        <br>
                        <a href="'.$url.'" download target="_blank"
                            style="display:inline-block;padding:4px 12px;background:#f59e42;color:#fff;border-radius:6px;font-size:12px;text-decoration:none;font-weight:600;box-shadow:0 1px 4px #0001;">
                            Download
                        </a>
                    </div>
                ';
            })->implode('');
        }
        // fallback jika hanya string
        $url = asset('storage/'.$state);
        return '
            <div style="display:inline-block;text-align:center;">
                <a href="'.$url.'" target="_blank">
                    <img src="'.$url.'" style="width:60px;height:60px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px #0001;margin-bottom:4px;">
                </a>
                <br>
                <a href="'.$url.'" download target="_blank"
                    style="display:inline-block;padding:4px 12px;background:#f59e42;color:#fff;border-radius:6px;font-size:12px;text-decoration:none;font-weight:600;box-shadow:0 1px 4px #0001;">
                    Download
                </a>
            </div>
        ';
    })
    ->html(),
   Tables\Columns\SelectColumn::make('status')
    ->label('Status')
    ->options([
        'pending' => 'Pending',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
    ])
    ->selectablePlaceholder(false)
    ->disabled(fn ($record) => optional($record->order)->status !== 'paid')
    ->afterStateUpdated(function ($state, $record) {
        // Tidak perlu cek lagi di sini, karena sudah dicegah di UI
        $record->status = $state;
        if ($state === 'verified') {
            $record->verified_at = now();
            $record->verified_by = auth()->id();
        } else {
            $record->verified_at = null;
            $record->verified_by = null;
        }
        $record->save();

              event(new \App\Events\PaymentUpdated($record));
        Notification::make()
            ->title('Status diperbarui')
            ->body("Status order #{$record->id} diubah menjadi {$state}")
            ->success()
            ->send();
    }),
                TextColumn::make('verifier.name')
                    ->label('Diverifikasi Oleh'),
                TextColumn::make('verified_at')
                    ->label('Waktu Verifikasi')
                         ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                         ->dateTime('d M Y H:i')
                    ->since(),
            ])
            ->filters([
                 Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
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
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentProofs::route('/'),
            'create' => Pages\CreatePaymentProof::route('/create'),
            'edit' => Pages\EditPaymentProof::route('/{record}/edit'),
        ];
    }
}
