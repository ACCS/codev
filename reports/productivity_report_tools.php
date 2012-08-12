<?php
/*
   This file is part of CoDev-Timetracking.

   CoDev-Timetracking is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   CoDev-Timetracking is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with CoDev-Timetracking.  If not, see <http://www.gnu.org/licenses/>.
*/

class ProductivityReportTools {

   /**
    * @param TimeTracking $timeTracking
    * @param int $projectId
    * @return mixed[]
    */
   public static function getProjectDetails(TimeTracking $timeTracking, $projectId) {
      $durationPerCategory = array();
      $formatedBugsPerCategory = array();

      $durPerCat = $timeTracking->getProjectDetails($projectId);
      foreach ($durPerCat as $catName => $bugList) {
         foreach ($bugList as $bugid => $duration) {
            $durationPerCategory[$catName] += $duration;

            if ($formatedBugsPerCategory[$catName] != "") { $formatedBugsPerCategory[$catName] .= ', '; }
            $issue = IssueCache::getInstance()->getIssue($bugid);
            $formatedBugsPerCategory[$catName] .= Tools::issueInfoURL($bugid, '['.$issue->getProjectName().'] '.$issue->summary);
         }
      }

      return self::getProjectDetail($durationPerCategory, $formatedBugsPerCategory);
   }

   /**
    * @param TimeTracking $timeTracking
    * @return mixed[]
    */
   public static function getSideTasksProjectDetails(TimeTracking $timeTracking) {
      // find all sideTasksProjects (type = 1)
      $team = TeamCache::getInstance()->getTeam($timeTracking->getTeamid());
      $projectIds = $team->getSpecificTypedProjectIds(Project::type_sideTaskProject);

      $durationPerCategory = array();
      $formatedBugsPerCategory = array();
      foreach($projectIds as $projectId) {
         $durPerCat = $timeTracking->getProjectDetails($projectId);
         foreach ($durPerCat as $catName => $bugList) {
            foreach ($bugList as $bugid => $duration) {
               $durationPerCategory[$catName] += $duration;

               if ($formatedBugsPerCategory[$catName] != "") { $formatedBugsPerCategory[$catName] .= ', '; }
               $issue = IssueCache::getInstance()->getIssue($bugid);
               $formatedBugsPerCategory[$catName] .= Tools::issueInfoURL($bugid, '['.$issue->getProjectName().'] '.$issue->summary);
            }
         }
      }

      return self::getProjectDetail($durationPerCategory, $formatedBugsPerCategory);
   }

   /**
    * @param int[] $durationPerCategory
    * @param string[] $formatedBugsPerCategory
    * @return mixed[]
    */
   public static function getProjectDetail(array $durationPerCategory, array $formatedBugsPerCategory) {
      $projectDetails = NULL;
      foreach ($durationPerCategory as $catName => $duration) {
         $projectDetails[] = array(
            'catName' => $catName,
            'duration' => $duration,
            'formatedBugsPerCategory' => $formatedBugsPerCategory[$catName]
         );
      }

      return $projectDetails;
   }

   /**
    * @param array[] $projectDetails
    * @return string
    */
   public static function getProjectDetailsUrl(array $projectDetails) {
      $formatedValues = NULL;
      $formatedLegends = NULL;
      foreach ($projectDetails as $projectDetail) {
         if (0 != $projectDetail['duration']) {
            if (NULL != $formatedValues) {
               $formatedValues .= ":"; $formatedLegends .= ":";
            }
            $formatedValues .= $projectDetail['duration'];
            $formatedLegends .= $projectDetail['catName'];
         }
      }

      if (NULL != $formatedValues) {
         return Tools::SmartUrlEncode("legends=$formatedLegends&values=$formatedValues");
      }
      return NULL;
   }

}

?>
