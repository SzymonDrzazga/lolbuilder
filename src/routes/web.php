<?php
use Illuminate\Support\Facades\Route;
use SzymonDrzazga\Lolbuilder\Controllers\LolBuilderController;

Route::prefix('lolbuilder')->group(function () {
    Route::get('/champs', [LolBuilderController::class, 'champs']);
    Route::get('/champ/{champ}', [LolBuilderController::class, 'champ']);
    Route::get('/random-champ', [LolBuilderController::class, 'randomChamp']);
});

?>
