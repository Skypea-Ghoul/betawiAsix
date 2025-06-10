<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Filament\Resources\OrderItemResource\RelationManagers;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 3;


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Jika role staff, batasi hanya order yang mengandung produk yang di‐assign ke staff tersebut.
        if ($user->role === 'staff') {
            return $query->whereHas('order.items.product', function (Builder $q) use ($user) {
                $q->whereHas('users', function (Builder $q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            });
        }

        // Admin (atau role lain) dapat melihat semua order
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        // 1. Pilih Order (order_id)
                        Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'id')
                            ->searchable()
                            ->required(),
                        // 2. Pilih Produk (product_id)
                        Select::make('product_id')
                            ->label('Produk')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                $product = Product::find($state);
                                $set('price', $product?->price ?? 0);
                                $quantity = $get('quantity') ?? 1;
                                $set('subtotal', ($product?->price ?? 0) * $quantity);
                            }),
                        // 3. Jumlah (quantity)
                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (callable $get, callable $set, $state) {
                                $price = $get('price') ?? 0;
                                $set('subtotal', $price * $state);
                            }),
                        // 4. Harga (price) — read-only but must be dehydrated so saved in DB
                        TextInput::make('price')
                            ->label('Harga (per unit)')
                            ->numeric()
                            ->disabled()
                            ->required()
                            ->dehydrated(true), // IMPORTANT: include this in form data
                        // 5. Subtotal — read-only, calculated
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->disabled()
                            ->required()
                            ->dehydrated(true), // include for validation or DB if needed
                    ])
                    ->columns(1),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.id')->label('Order ID'),
                Tables\Columns\TextColumn::make('order.user.name')->label('Nama Pelanggan'),
                Tables\Columns\TextColumn::make('product.name')->label('Produk'),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga (per unit)')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('order_id')
                    ->label('Order')
                    ->options(OrderItem::query()->with('order')->get()->pluck('order.id', 'order.id')),
                Tables\Filters\SelectFilter::make('order.user.name')
                    ->label('Nama Pelanggan')
                    ->options(OrderItem::query()->with('order.user')->get()->pluck('order.user.name', 'order.user.name')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrderItems::route('/'),
            'create' => Pages\CreateOrderItem::route('/create'),
            'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
