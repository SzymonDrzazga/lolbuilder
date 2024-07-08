<?php

namespace SzymonDrzazga\lolbuilder;

use Illuminate\Support\Facades\Http;
use Exception;

class lolbuilder
{
    protected $version = '';
    protected $lang = 'en_US';

    public function __construct()
    {
        $this->version = $this->fetchLatestVersion();
    }

    public function fetchLatestVersion()
    {
        try {
            $response = Http::get('https://ddragon.leagueoflegends.com/api/versions.json');
            if ($response->failed()) {
                throw new Exception('Failed to fetch latest version.');
            }
            return $response->json()[0];
        } catch (Exception $e) {
            return 'Error fetching latest version: ' . $e->getMessage();
        }
    }

    public function champs($version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/champion.json");
            if ($response->failed()) {
                throw new Exception('Failed to fetch champions.');
            }
            return $response->json()['data'];
        } catch (Exception $e) {
            return 'Error fetching champions: ' . $e->getMessage();
        }
    }

    public function champ($champ, $version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;
        $logo = is_array($champ) ? $champ['id'] : $champ;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/champion/$logo.json");
            if ($response->failed()) {
                throw new Exception("Failed to fetch champion data for: $logo.");
            }
            return $response->json()['data'][$logo];
        } catch (Exception $e) {
            return 'Error fetching champion data: ' . $e->getMessage();
        }
    }

    public function champIcon($champ = '', $version = '') {
        $version = $version ?: $this->version;
        $logo = is_array($champ) ? $champ['image']['full'] : $champ;

        try {
            if (empty($logo)) {
                throw new Exception('Champion image not provided.');
            }
            return "http://ddragon.leagueoflegends.com/cdn/$version/img/champion/$logo";
        } catch (Exception $e) {
            return 'Error fetching champion icon: ' . $e->getMessage();
        }
    }

    public function randomChamp($version = '', $lang = '') {
        try {
            $champs = $this->champs($version, $lang);
            if (empty($champs)) {
                throw new Exception('No champions data available.');
            }
            $champs = array_values($champs);
            return $champs[array_rand($champs)];
        } catch (Exception $e) {
            return 'Error fetching random champion: ' . $e->getMessage();
        }
    }

    public function randomItems($version = '', $lang = '') {
        try {
            $items = $this->items($version, $lang);
            if (empty($items)) {
                throw new Exception('No items data available.');
            }
            $items = array_values($items);
            return array_rand($items, 6);
        } catch (Exception $e) {
            return 'Error fetching random items: ' . $e->getMessage();
        }
    }

    public function randomRunes($version = '', $lang = '') {
        try {
            $runes = $this->runes($version, $lang);
            if (empty($runes)) {
                throw new Exception('No runes data available.');
            }
            $runes = array_values($runes);
            $primary = array_rand($runes);
            $secondary = array_rand($runes);
            return [$runes[$primary], $runes[$secondary]];
        } catch (Exception $e) {
            return 'Error fetching random runes: ' . $e->getMessage();
        }
    }

    public function randomSummonerSpells($version = '', $lang = '') {
        try {
            $summonerSpells = $this->summonerSpells($version, $lang);
            if (empty($summonerSpells)) {
                throw new Exception('No summoner spells data available.');
            }
            $summonerSpells = array_values($summonerSpells);
            return array_rand($summonerSpells, 2);
        } catch (Exception $e) {
            return 'Error fetching random summoner spells: ' . $e->getMessage();
        }
    }

    public function randomBuild($champ = '', $version = '', $lang = '') {
        try {
            $champ = $this->champ($champ, $version, $lang);
            if (empty($champ)) {
                throw new Exception('No champion data available.');
            }
            $build = [
                'champion' => $champ,
                'items' => $this->randomItems(),
                'runes' => $this->randomRunes(),
                'summonerSpells' => $this->randomSummonerSpells()
            ];
            return $build;
        } catch (Exception $e) {
            return 'Error fetching random build: ' . $e->getMessage();
        }
    }

    public function items($version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/item.json");
            if ($response->failed()) {
                throw new Exception('Failed to fetch items.');
            }
            return $response->json()['data'];
        } catch (Exception $e) {
            return 'Error fetching items: ' . $e->getMessage();
        }
    }

    public function itemIcon($item = '', $version = '') {
        $version = $version ?: $this->version;
        $logo = is_array($item) ? $item['image']['full'] : $item;

        try {
            if (empty($logo)) {
                throw new Exception('Item image not provided.');
            }
            return "http://ddragon.leagueoflegends.com/cdn/$version/img/item/$logo";
        } catch (Exception $e) {
            return 'Error fetching item icon: ' . $e->getMessage();
        }
    }

    public function runes($version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/runesReforged.json");
            if ($response->failed()) {
                throw new Exception('Failed to fetch runes.');
            }
            return $response->json();
        } catch (Exception $e) {
            return 'Error fetching runes: ' . $e->getMessage();
        }
    }

    public function runeIcon($rune = '') {
        try {
            if (is_array($rune) && isset($rune['icon'])) {
                return "http://ddragon.leagueoflegends.com/cdn/img/" . $rune['icon'];
            } else {
                return "http://ddragon.leagueoflegends.com/cdn/img/perk-images/Styles/7200_Domination.png";
            }
        } catch (Exception $e) {
            return 'Error fetching rune icon: ' . $e->getMessage();
        }
    }

    public function masteries($version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/mastery.json");
            if ($response->failed()) {
                throw new Exception('Failed to fetch masteries.');
            }
            return $response->json()['data'];
        } catch (Exception $e) {
            return 'Error fetching masteries: ' . $e->getMessage();
        }
    }

    public function masteryIcon($mastery = '', $version = '') {
        $version = $version ?: $this->version;
        $logo = is_array($mastery) ? $mastery['image']['full'] : $mastery;

        try {
            if (empty($logo)) {
                throw new Exception('Mastery image not provided.');
            }
            return "http://ddragon.leagueoflegends.com/cdn/$version/img/mastery/$logo";
        } catch (Exception $e) {
            return 'Error fetching mastery icon: ' . $e->getMessage();
        }
    }

    public function summonerSpells($version = '', $lang = '') {
        $version = $version ?: $this->version;
        $lang = $lang ?: $this->lang;

        try {
            $response = Http::get("http://ddragon.leagueoflegends.com/cdn/$version/data/$lang/summoner.json");
            if ($response->failed()) {
                throw new Exception('Failed to fetch summoner spells.');
            }
            return $response->json()['data'];
        } catch (Exception $e) {
            return 'Error fetching summoner spells: ' . $e->getMessage();
        }
    }

    public function summonerSpellIcon($summonerSpell = '', $version = '') {
        $version = $version ?: $this->version;
        $logo = is_array($summonerSpell) ? $summonerSpell['image']['full'] : $summonerSpell;

        try {
            if (empty($logo)) {
                throw new Exception('Summoner spell image not provided.');
            }
            return "http://ddragon.leagueoflegends.com/cdn/$version/img/spell/$logo";
        } catch (Exception $e) {
            return 'Error fetching summoner spell icon: ' . $e->getMessage();
        }
    }

    public function setLanguage($lang){
        $this->lang = $lang;
    }

    public function setVersion($version){
        $this->version = $version;
    }

    public function getLanguage(){
        return $this->lang;
    }

    public function getVersion(){
        return $this->version;
    }
}
?>
