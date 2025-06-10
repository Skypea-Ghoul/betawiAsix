<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->role === 'staff') {
            return $query->whereHas('items.product', function (Builder $q) use ($user) {
                $q->whereHas('users', function (Builder $q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            });
        }

        return $query;
    }

    private static function calculateTotal(callable $get, callable $set): void
    {
        $items = $get('items') ?? [];
        $total = 0;
        foreach ($items as $item) {
            $subtotal = $item['subtotal'] ?? 0;
            $total += $subtotal;
        }
        $set('total_price', $total);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        FormSelect::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        FormSelect::make('status')
                            ->label('Status')
                            ->options([
                                'pending'   => 'Pending',
                                'paid'      => 'Paid',
                                'canceled'  => 'Canceled',
                            ])
                            ->default('pending')
                            ->required(),

                        Repeater::make('items')
                            ->relationship()
                            ->label('Produk yang dipesan')
                            ->schema([
                                FormSelect::make('product_id')
                                    ->label('Produk')
                                    ->options(fn() => Product::where('stock', '>', 0)
                                        ->get()
                                        ->mapWithKeys(fn($product) => [
                                            $product->id => "{$product->name} (Stok: {$product->stock})",
                                        ])
                                    )
                                    ->searchable()
                                    ->reactive()
                                    ->required()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get, $record) {
                                        if ($record) {
                                            $set('price', $record->price ?? 0);
                                            $set('quantity', $record->quantity ?? 1);
                                            $set('subtotal', $record->subtotal ?? 0);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                        if ($record && $state === $record->product_id) {
                                            return;
                                        }
                                        $product = Product::find($state);
                                        if (! $product) {
                                            $set('price', 0);
                                            $set('quantity', 1);
                                            $set('subtotal', 0);
                                            self::calculateTotal(fn($path) => $get($path), fn($path, $value) => $set($path, $value));
                                            return;
                                        }
                                        $set('price', $product->price);
                                        $quantity = $get('quantity') ?? 1;
                                        if ($quantity > $product->stock) {
                                            $quantity = $product->stock;
                                            $set('quantity', $product->stock);
                                            Notification::make()
                                                ->title('Peringatan Stok')
                                                ->body("Quantity disesuaikan dengan stok tersedia: {$product->stock}")
                                                ->warning()
                                                ->send();
                                        }
                                        $set('subtotal', $product->price * $quantity);
                                        self::calculateTotal(fn($path) => $get($path), fn($path, $value) => $set($path, $value));
                                    }),

                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->minValue(1)
                                    ->reactive()
                                    ->required()
                                    ->afterStateHydrated(function ($state, callable $set, callable $get, $record) {
                                        if ($record) {
                                            $set('quantity', $record->quantity ?? 1);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                                        if ($record && $state === $record->quantity) {
                                            return;
                                        }
                                        $product = Product::find($get('product_id'));
                                        $newQuantity = (int) $state;
                                        if ($product && $newQuantity > $product->stock) {
                                            $newQuantity = $product->stock;
                                            $set('quantity', $product->stock);
                                            Notification::make()
                                                ->title('Peringatan Stok')
                                                ->body("Quantity disesuaikan dengan stok tersedia: {$product->stock}")
                                                ->warning()
                                                ->send();
                                        }
                                        $price = $get('price') ?? 0;
                                        $set('subtotal', $price * $newQuantity);
                                        self::calculateTotal(fn($path) => $get($path), fn($path, $value) => $set($path, $value));
                                    })
                                    ->helperText(fn($get) => $get('product_id') ? (Product::find($get('product_id')) ? "Stok tersedia: " . Product::find($get('product_id'))->stock : '') : ''),

                                TextInput::make('price')
                                    ->label('Harga (per unit)')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->required()
                                    ->afterStateHydrated(fn($state, callable $set, callable $get, $record) => $record && $set('price', $record->price ?? 0))
                                    ->dehydrated(true),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->required()
                                    ->afterStateHydrated(fn($state, callable $set, callable $get, $record) => $record && $set('subtotal', $record->subtotal ?? 0))
                                    ->dehydrated(true),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->columnSpan('full')
                            ->columns(4)
                            ->afterStateUpdated(fn($get, $set) => ! $get('id') && self::calculateTotal($get, $set))
                            ->addActionLabel('Tambah Item')
                            ->reorderable()
                            ->collapsible()
                            ->cloneable(),

                        TextInput::make('total_price')
                            ->label('Total Harga')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->required()
                            ->afterStateHydrated(fn($state, callable $set, callable $get, $record) => $record && $set('total_price', $record->items->sum(fn($i) => $i->subtotal ?? 0)))
                            ->dehydrated(true),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Order')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'canceled'  => 'Canceled',
                    ])
                    ->selectablePlaceholder(false) // Nonaktifkan placeholder
                    ->afterStateUpdated(function ($state, $record) {
                        // Simpan perubahan secara otomatis
                        $record->status = $state;
                        $record->save();
                        
                        // Notifikasi perubahan
                        // Make sure to import this at the top: use App\Events\OrderUpdated;
                        event(new \App\Events\OrderUpdated($record)); 
                        Notification::make()
                            ->title('Status diperbarui')
                            ->body("Status order #{$record->id} diubah menjadi {$state}")
                            ->success()
                            ->send();
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('grandtotal')
                    ->label('Grand Total')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'paid'      => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\TernaryFilter::make('grandtotal')
                    ->label('Hanya Order dengan Grand Total')
                    ->trueLabel('Ada Grand Total')
                    ->falseLabel('Tanpa Grand Total')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('grandtotal')->where('grandtotal', '>', 0),
                        false: fn($query) => $query->whereNull('grandtotal')->orWhere('grandtotal', 0)
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Order')
                    ->modalDescription('Apakah Anda yakin ingin menghapus order ini?')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Orders')
                    ->modalDescription('Apakah Anda yakin ingin menghapus orders yang dipilih?')
                    ->modalSubmitActionLabel('Ya, Hapus'),
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
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
