<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new crMenu(EWR_MENUBAR_ID); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(9, "mi_Sales", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("9", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Salessmry.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(12, "mi_Donations_Report", $ReportLanguage->Phrase("DetailSummaryReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("12", "MenuText") . $ReportLanguage->Phrase("DetailSummaryReportMenuItemSuffix"), "Donations_Reportsmry.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
