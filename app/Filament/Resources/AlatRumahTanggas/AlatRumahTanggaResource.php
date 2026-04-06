<?php

namespace App\Filament\Resources\AlatRumahTanggas;

use App\Filament\Resources\AlatRumahTanggas\Pages;
use App\Filament\Resources\AlatRumahTanggas\Pages\CreateAlatRumahTangga;
use App\Filament\Resources\AlatRumahTanggas\Pages\EditAlatRumahTangga;
use App\Filament\Resources\AlatRumahTanggas\Pages\ListAlatRumahTanggas;
use App\Filament\Resources\AlatRumahTanggas\Pages\ViewAlatRumahTangga; 
use App\Filament\Resources\AlatRumahTanggas\Schemas\AlatRumahTanggaForm;
use App\Filament\Resources\AlatRumahTanggas\Schemas\AlatRumahTanggaInfolist;
use App\Filament\Resources\AlatRumahTanggas\Tables\AlatRumahTanggaTable;
use App\Models\Perangkat;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon; // Gunakan library icon bawaan jika UnitEnum error
use UnitEnum;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User as AppUser;
use Illuminate\Database\Eloquent\Model;

class AlatRumahTanggaResource extends Resource
{
    protected static ?string $model = Perangkat::class;

    // Kita gunakan string biasa untuk ikon agar aman dari error enum
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';
    
    // String biasa untuk grup navigasi
    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Perangkat';

    protected static ?string $recordTitleAttribute = 'nama_perangkat';
    protected static ?string $modelLabel = 'Data Perangkat';
    protected static ?string $pluralModelLabel = 'Data Perangkat';
    protected static ?int $navigationSort = 1;

    // Sambungkan ke File Form yang baru
    public static function form(Schema $schema): Schema
    {
        return AlatRumahTanggaForm::configure($schema);
    }

    // Sambungkan ke File Infolist yang baru
    public static function infolist(Schema $schema): Schema
    {
        return AlatRumahTanggaInfolist::configure($schema);
    }

    // Sambungkan ke File Table yang baru
    public static function table(Table $table): Table
    {
        return AlatRumahTanggaTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        // Pastikan route ini sesuai dengan file Page yang akan direname di Tahap 5
        return [
            'index' => ListAlatRumahTanggas::route('/'),
            'create' => CreateAlatRumahTangga::route('/create'),
            'resume' => Pages\ResumeAlatRumahTangga::route('/resume'),
            'view' => ViewAlatRumahTangga::route('/{record}'),
            'edit' => EditAlatRumahTangga::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
         $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user instanceof AppUser && $user->canDo('perangkat.manage');
    }
}