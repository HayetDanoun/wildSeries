<?php
namespace App\Service;

use App\Entity\Program;
class ProgramDuration
{

    function calculate(Program $program) :string
    {
        $tempsVisionage = 0;
        $seasons = $program->getSeasons();
        foreach ($seasons as $season) {
            foreach ($season->getEpisodes() as $episode) {
                $tempsVisionage += $episode->getDuration();
            }
        }

        $days = intval($tempsVisionage / (24 * 60));
        $remaining_minutes = $tempsVisionage % (24 * 60);
        $hours = intval($remaining_minutes / 60);
        $minutes = $remaining_minutes % 60;

        $result = "{$days} jour" . ($days > 1 ? "s" : "") . " {$hours} heure" . ($hours > 1 ? "s" : "") . " et {$minutes} minute" . ($minutes > 1 ? "s" : "");
        return $result;
    }

}

//1ere version
//$tempsVisionage=1610;
//$jours = floor($tempsVisionage/(24*60));
//$heures = floor(($tempsVisionage - $jours*(24*60))/60);
//$minutes = ($tempsVisionage - $jours*(24*60) )%60;
//echo 'Total 1610min = 1jour/2h/50min'. '<br>'.'<br>';
//echo 'nbjours='. $jours  .'<br>';
//echo 'nbheure=' . $heures  .'<br>';
//echo 'nbmin=' . $minutes  .'<br>';