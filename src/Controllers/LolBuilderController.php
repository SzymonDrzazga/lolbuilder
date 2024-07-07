<?php
namespace SzymonDrzazga\Lolbuilder\Controllers;

use App\Http\Controllers\Controller;
use SzymonDrzazga\Lolbuilder\LolBuilder;
use Illuminate\Http\Request;

class LolBuilderController extends Controller
{
    protected $lolBuilderService;

    public function __construct(LolBuilder $lolBuilderService)
    {
        $this->lolBuilderService = $lolBuilderService;
    }

    public function champs()
    {
        $champs = $this->lolBuilderService->champs();
        return view('lolbuilder::champs.index', ['champs' => $champs]);
    }

    public function champ($champ)
    {
        $champData = $this->lolBuilderService->champ($champ);
        return view('lolbuilder::champs.show', ['champ' => $champData]);
    }

    public function randomChamp()
    {
        $champ = $this->lolBuilderService->randomChamp();
        return view('lolbuilder::champs.randomizer', ['champ' => $champ]);
    }

}

?>
