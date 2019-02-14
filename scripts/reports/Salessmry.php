<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg9.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn9.php" ?>
<?php include_once "phprptinc/ewrusrfn9.php" ?>
<?php include_once "Salessmryinfo.php" ?>
<?php

//
// Page class
//

$Sales_summary = NULL; // Initialize page object first

class crSales_summary extends crSales {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{502C7FE7-7939-4DA2-B926-2E1B54AA4C6C}";

	// Page object name
	var $PageObjName = 'Sales_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $ReportLanguage;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (Sales)
		if (!isset($GLOBALS["Sales"])) {
			$GLOBALS["Sales"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Sales"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'Sales', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fSalessummary";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $ReportLanguage;
		$exportid = session_id();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = TRUE;
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;
		// Export to Email

		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_Sales\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_Sales',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fSalessummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = FALSE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fSalessummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fSalessummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				ob_end_clean();
				echo $this->$fn($sContent);
				ewr_CloseConn(); // Close connection
				exit();
			} else {
				$this->$fn($sContent);
			}
		}

		 // Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 10; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpFldCount = 0;
	var $SubGrpFldCount = 0;
	var $DtlFldCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 9;
		$nGrps = 1;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		$this->name->SelectionList = "";
		$this->name->DefaultSelectionList = "";
		$this->name->ValueList = "";
		$this->user->SelectionList = "";
		$this->user->DefaultSelectionList = "";
		$this->user->ValueList = "";
		$this->ordertype->SelectionList = "";
		$this->ordertype->DefaultSelectionList = "";
		$this->ordertype->ValueList = "";
		$this->transactiondate->SelectionList = "";
		$this->transactiondate->DefaultSelectionList = "";
		$this->transactiondate->ValueList = "";
		$this->ordernumber->SelectionList = "";
		$this->ordernumber->DefaultSelectionList = "";
		$this->ordernumber->ValueList = "";
		$this->status->SelectionList = "";
		$this->status->DefaultSelectionList = "";
		$this->status->ValueList = "";
		$this->platform->SelectionList = "";
		$this->platform->DefaultSelectionList = "";
		$this->platform->ValueList = "";
		$this->transactiontype->SelectionList = "";
		$this->transactiontype->DefaultSelectionList = "";
		$this->transactiontype->ValueList = "";

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);
		$this->SearchOptions->GetItem("resetfilter")->Visible = $this->FilterApplied;

		// Get sort
		$this->Sort = $this->GetSort();

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = TRUE;

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
		    $this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup(); 

		// Set no record found message
		if ($this->TotalGrps == 0) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get count
	function GetCnt($sql) {
		$conn = &$this->Connection();
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row

	//		$rs->MoveFirst(); // NOTE: no need to move position
				$this->FirstRowData = array();
				$this->FirstRowData['name'] = ewr_Conv($rs->fields('name'),200);
				$this->FirstRowData['user'] = ewr_Conv($rs->fields('user'),200);
				$this->FirstRowData['ordertype'] = ewr_Conv($rs->fields('ordertype'),131);
				$this->FirstRowData['transactiondate'] = ewr_Conv($rs->fields('transactiondate'),135);
				$this->FirstRowData['ordernumber'] = ewr_Conv($rs->fields('ordernumber'),200);
				$this->FirstRowData['status'] = ewr_Conv($rs->fields('status'),200);
				$this->FirstRowData['platform'] = ewr_Conv($rs->fields('platform'),200);
				$this->FirstRowData['transactiontype'] = ewr_Conv($rs->fields('transactiontype'),200);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->name->setDbValue($rs->fields('name'));
			$this->user->setDbValue($rs->fields('user'));
			$this->ordertype->setDbValue($rs->fields('ordertype'));
			$this->transactiondate->setDbValue($rs->fields('transactiondate'));
			$this->ordernumber->setDbValue($rs->fields('ordernumber'));
			$this->status->setDbValue($rs->fields('status'));
			$this->platform->setDbValue($rs->fields('platform'));
			$this->transactiontype->setDbValue($rs->fields('transactiontype'));
			$this->Val[1] = $this->name->CurrentValue;
			$this->Val[2] = $this->user->CurrentValue;
			$this->Val[3] = $this->ordertype->CurrentValue;
			$this->Val[4] = $this->transactiondate->CurrentValue;
			$this->Val[5] = $this->ordernumber->CurrentValue;
			$this->Val[6] = $this->status->CurrentValue;
			$this->Val[7] = $this->platform->CurrentValue;
			$this->Val[8] = $this->transactiontype->CurrentValue;
		} else {
			$this->name->setDbValue("");
			$this->user->setDbValue("");
			$this->ordertype->setDbValue("");
			$this->transactiondate->setDbValue("");
			$this->ordernumber->setDbValue("");
			$this->status->setDbValue("");
			$this->platform->setDbValue("");
			$this->transactiontype->setDbValue("");
		}
	}

	//  Set up starting group
	function SetUpStartGroup() {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;

		// Check for a 'start' parameter
		if (@$_GET[EWR_TABLE_START_GROUP] != "") {
			$this->StartGrp = $_GET[EWR_TABLE_START_GROUP];
			$this->setStartGroup($this->StartGrp);
		} elseif (@$_GET["pageno"] != "") {
			$nPageNo = $_GET["pageno"];
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Build distinct values for name

			if ($popupname == 'Sales_name') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->name, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->name->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->name->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->name->setDbValue($rswrk->fields[0]);
					if (is_null($this->name->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->name->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->name->ViewValue = $this->name->CurrentValue;
						ewr_SetupDistinctValues($this->name->ValueList, $this->name->CurrentValue, $this->name->ViewValue, FALSE, $this->name->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->name->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->name->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->name;
			}

			// Build distinct values for user
			if ($popupname == 'Sales_user') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->user, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->user->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->user->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->user->setDbValue($rswrk->fields[0]);
					if (is_null($this->user->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->user->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->user->ViewValue = $this->user->CurrentValue;
						ewr_SetupDistinctValues($this->user->ValueList, $this->user->CurrentValue, $this->user->ViewValue, FALSE, $this->user->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->user->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->user->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->user;
			}

			// Build distinct values for ordertype
			if ($popupname == 'Sales_ordertype') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ordertype, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ordertype->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ordertype->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ordertype->setDbValue($rswrk->fields[0]);
					if (is_null($this->ordertype->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ordertype->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->ordertype->ViewValue = $this->ordertype->CurrentValue;
						ewr_SetupDistinctValues($this->ordertype->ValueList, $this->ordertype->CurrentValue, $this->ordertype->ViewValue, FALSE, $this->ordertype->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ordertype->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ordertype->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ordertype;
			}

			// Build distinct values for transactiondate
			if ($popupname == 'Sales_transactiondate') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->transactiondate, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->transactiondate->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->transactiondate->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->transactiondate->setDbValue($rswrk->fields[0]);
					if (is_null($this->transactiondate->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->transactiondate->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->transactiondate->ViewValue = ewr_FormatDateTime($this->transactiondate->CurrentValue, 3);
						ewr_SetupDistinctValues($this->transactiondate->ValueList, $this->transactiondate->CurrentValue, $this->transactiondate->ViewValue, FALSE, $this->transactiondate->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->transactiondate->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->transactiondate->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->transactiondate;
			}

			// Build distinct values for ordernumber
			if ($popupname == 'Sales_ordernumber') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->ordernumber, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->ordernumber->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->ordernumber->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->ordernumber->setDbValue($rswrk->fields[0]);
					if (is_null($this->ordernumber->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->ordernumber->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->ordernumber->ViewValue = $this->ordernumber->CurrentValue;
						ewr_SetupDistinctValues($this->ordernumber->ValueList, $this->ordernumber->CurrentValue, $this->ordernumber->ViewValue, FALSE, $this->ordernumber->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->ordernumber->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->ordernumber->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->ordernumber;
			}

			// Build distinct values for status
			if ($popupname == 'Sales_status') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->status, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->status->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->status->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->status->setDbValue($rswrk->fields[0]);
					if (is_null($this->status->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->status->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->status->ViewValue = $this->status->CurrentValue;
						ewr_SetupDistinctValues($this->status->ValueList, $this->status->CurrentValue, $this->status->ViewValue, FALSE, $this->status->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->status->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->status->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->status;
			}

			// Build distinct values for platform
			if ($popupname == 'Sales_platform') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->platform, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->platform->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->platform->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->platform->setDbValue($rswrk->fields[0]);
					if (is_null($this->platform->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->platform->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->platform->ViewValue = $this->platform->CurrentValue;
						ewr_SetupDistinctValues($this->platform->ValueList, $this->platform->CurrentValue, $this->platform->ViewValue, FALSE, $this->platform->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->platform->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->platform->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->platform;
			}

			// Build distinct values for transactiontype
			if ($popupname == 'Sales_transactiontype') {
				$bNullValue = FALSE;
				$bEmptyValue = FALSE;
				$sFilter = $this->Filter;

				// Call Page Filtering event
				$this->Page_Filtering($this->transactiontype, $sFilter, "popup");
				$sSql = ewr_BuildReportSql($this->transactiontype->SqlSelect, $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->transactiontype->SqlOrderBy, $sFilter, "");
				$rswrk = $conn->Execute($sSql);
				while ($rswrk && !$rswrk->EOF) {
					$this->transactiontype->setDbValue($rswrk->fields[0]);
					if (is_null($this->transactiontype->CurrentValue)) {
						$bNullValue = TRUE;
					} elseif ($this->transactiontype->CurrentValue == "") {
						$bEmptyValue = TRUE;
					} else {
						$this->transactiontype->ViewValue = $this->transactiontype->CurrentValue;
						ewr_SetupDistinctValues($this->transactiontype->ValueList, $this->transactiontype->CurrentValue, $this->transactiontype->ViewValue, FALSE, $this->transactiontype->FldDelimiter);
					}
					$rswrk->MoveNext();
				}
				if ($rswrk)
					$rswrk->Close();
				if ($bEmptyValue)
					ewr_SetupDistinctValues($this->transactiontype->ValueList, EWR_EMPTY_VALUE, $ReportLanguage->Phrase("EmptyLabel"), FALSE);
				if ($bNullValue)
					ewr_SetupDistinctValues($this->transactiontype->ValueList, EWR_NULL_VALUE, $ReportLanguage->Phrase("NullLabel"), FALSE);
				$fld = &$this->transactiontype;
			}

			// Output data as Json
			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ClearSessionSelection('name');
				$this->ClearSessionSelection('user');
				$this->ClearSessionSelection('ordertype');
				$this->ClearSessionSelection('transactiondate');
				$this->ClearSessionSelection('ordernumber');
				$this->ClearSessionSelection('status');
				$this->ClearSessionSelection('platform');
				$this->ClearSessionSelection('transactiontype');
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
		// Get name selected values

		if (is_array(@$_SESSION["sel_Sales_name"])) {
			$this->LoadSelectionFromSession('name');
		} elseif (@$_SESSION["sel_Sales_name"] == EWR_INIT_VALUE) { // Select all
			$this->name->SelectionList = "";
		}

		// Get user selected values
		if (is_array(@$_SESSION["sel_Sales_user"])) {
			$this->LoadSelectionFromSession('user');
		} elseif (@$_SESSION["sel_Sales_user"] == EWR_INIT_VALUE) { // Select all
			$this->user->SelectionList = "";
		}

		// Get ordertype selected values
		if (is_array(@$_SESSION["sel_Sales_ordertype"])) {
			$this->LoadSelectionFromSession('ordertype');
		} elseif (@$_SESSION["sel_Sales_ordertype"] == EWR_INIT_VALUE) { // Select all
			$this->ordertype->SelectionList = "";
		}

		// Get transactiondate selected values
		if (is_array(@$_SESSION["sel_Sales_transactiondate"])) {
			$this->LoadSelectionFromSession('transactiondate');
		} elseif (@$_SESSION["sel_Sales_transactiondate"] == EWR_INIT_VALUE) { // Select all
			$this->transactiondate->SelectionList = "";
		}

		// Get ordernumber selected values
		if (is_array(@$_SESSION["sel_Sales_ordernumber"])) {
			$this->LoadSelectionFromSession('ordernumber');
		} elseif (@$_SESSION["sel_Sales_ordernumber"] == EWR_INIT_VALUE) { // Select all
			$this->ordernumber->SelectionList = "";
		}

		// Get status selected values
		if (is_array(@$_SESSION["sel_Sales_status"])) {
			$this->LoadSelectionFromSession('status');
		} elseif (@$_SESSION["sel_Sales_status"] == EWR_INIT_VALUE) { // Select all
			$this->status->SelectionList = "";
		}

		// Get platform selected values
		if (is_array(@$_SESSION["sel_Sales_platform"])) {
			$this->LoadSelectionFromSession('platform');
		} elseif (@$_SESSION["sel_Sales_platform"] == EWR_INIT_VALUE) { // Select all
			$this->platform->SelectionList = "";
		}

		// Get transactiontype selected values
		if (is_array(@$_SESSION["sel_Sales_transactiontype"])) {
			$this->LoadSelectionFromSession('transactiontype');
		} elseif (@$_SESSION["sel_Sales_transactiontype"] == EWR_INIT_VALUE) { // Select all
			$this->transactiontype->SelectionList = "";
		}
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 10; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 10; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->RowTotalType == EWR_ROWTOTAL_GRAND && !$this->GrandSummarySetup) { // Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandSmry[3] = $rsagg->fields("sum_ordertype");
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandCnt[7] = $this->TotCount;
				$this->GrandCnt[8] = $this->TotCount;
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row
			$this->RowAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel; // Set up row class

			// ordertype
			$this->ordertype->SumViewValue = $this->ordertype->SumValue;
			$this->ordertype->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// name
			$this->name->HrefValue = "";

			// user
			$this->user->HrefValue = "";

			// ordertype
			$this->ordertype->HrefValue = "";

			// transactiondate
			$this->transactiondate->HrefValue = "";

			// ordernumber
			$this->ordernumber->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// platform
			$this->platform->HrefValue = "";

			// transactiontype
			$this->transactiontype->HrefValue = "";
		} else {

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// user
			$this->user->ViewValue = $this->user->CurrentValue;
			$this->user->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ordertype
			$this->ordertype->ViewValue = $this->ordertype->CurrentValue;
			$this->ordertype->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// transactiondate
			$this->transactiondate->ViewValue = $this->transactiondate->CurrentValue;
			$this->transactiondate->ViewValue = ewr_FormatDateTime($this->transactiondate->ViewValue, 3);
			$this->transactiondate->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// ordernumber
			$this->ordernumber->ViewValue = $this->ordernumber->CurrentValue;
			$this->ordernumber->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// status
			$this->status->ViewValue = $this->status->CurrentValue;
			$this->status->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// platform
			$this->platform->ViewValue = $this->platform->CurrentValue;
			$this->platform->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// transactiontype
			$this->transactiontype->ViewValue = $this->transactiontype->CurrentValue;
			$this->transactiontype->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// name
			$this->name->HrefValue = "";

			// user
			$this->user->HrefValue = "";

			// ordertype
			$this->ordertype->HrefValue = "";

			// transactiondate
			$this->transactiondate->HrefValue = "";

			// ordernumber
			$this->ordernumber->HrefValue = "";

			// status
			$this->status->HrefValue = "";

			// platform
			$this->platform->HrefValue = "";

			// transactiontype
			$this->transactiontype->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// ordertype
			$CurrentValue = $this->ordertype->SumValue;
			$ViewValue = &$this->ordertype->SumViewValue;
			$ViewAttrs = &$this->ordertype->ViewAttrs;
			$CellAttrs = &$this->ordertype->CellAttrs;
			$HrefValue = &$this->ordertype->HrefValue;
			$LinkAttrs = &$this->ordertype->LinkAttrs;
			$this->Cell_Rendered($this->ordertype, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// name
			$CurrentValue = $this->name->CurrentValue;
			$ViewValue = &$this->name->ViewValue;
			$ViewAttrs = &$this->name->ViewAttrs;
			$CellAttrs = &$this->name->CellAttrs;
			$HrefValue = &$this->name->HrefValue;
			$LinkAttrs = &$this->name->LinkAttrs;
			$this->Cell_Rendered($this->name, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// user
			$CurrentValue = $this->user->CurrentValue;
			$ViewValue = &$this->user->ViewValue;
			$ViewAttrs = &$this->user->ViewAttrs;
			$CellAttrs = &$this->user->CellAttrs;
			$HrefValue = &$this->user->HrefValue;
			$LinkAttrs = &$this->user->LinkAttrs;
			$this->Cell_Rendered($this->user, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ordertype
			$CurrentValue = $this->ordertype->CurrentValue;
			$ViewValue = &$this->ordertype->ViewValue;
			$ViewAttrs = &$this->ordertype->ViewAttrs;
			$CellAttrs = &$this->ordertype->CellAttrs;
			$HrefValue = &$this->ordertype->HrefValue;
			$LinkAttrs = &$this->ordertype->LinkAttrs;
			$this->Cell_Rendered($this->ordertype, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// transactiondate
			$CurrentValue = $this->transactiondate->CurrentValue;
			$ViewValue = &$this->transactiondate->ViewValue;
			$ViewAttrs = &$this->transactiondate->ViewAttrs;
			$CellAttrs = &$this->transactiondate->CellAttrs;
			$HrefValue = &$this->transactiondate->HrefValue;
			$LinkAttrs = &$this->transactiondate->LinkAttrs;
			$this->Cell_Rendered($this->transactiondate, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// ordernumber
			$CurrentValue = $this->ordernumber->CurrentValue;
			$ViewValue = &$this->ordernumber->ViewValue;
			$ViewAttrs = &$this->ordernumber->ViewAttrs;
			$CellAttrs = &$this->ordernumber->CellAttrs;
			$HrefValue = &$this->ordernumber->HrefValue;
			$LinkAttrs = &$this->ordernumber->LinkAttrs;
			$this->Cell_Rendered($this->ordernumber, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// status
			$CurrentValue = $this->status->CurrentValue;
			$ViewValue = &$this->status->ViewValue;
			$ViewAttrs = &$this->status->ViewAttrs;
			$CellAttrs = &$this->status->CellAttrs;
			$HrefValue = &$this->status->HrefValue;
			$LinkAttrs = &$this->status->LinkAttrs;
			$this->Cell_Rendered($this->status, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// platform
			$CurrentValue = $this->platform->CurrentValue;
			$ViewValue = &$this->platform->ViewValue;
			$ViewAttrs = &$this->platform->ViewAttrs;
			$CellAttrs = &$this->platform->CellAttrs;
			$HrefValue = &$this->platform->HrefValue;
			$LinkAttrs = &$this->platform->LinkAttrs;
			$this->Cell_Rendered($this->platform, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// transactiontype
			$CurrentValue = $this->transactiontype->CurrentValue;
			$ViewValue = &$this->transactiontype->ViewValue;
			$ViewAttrs = &$this->transactiontype->ViewAttrs;
			$CellAttrs = &$this->transactiontype->CellAttrs;
			$HrefValue = &$this->transactiontype->HrefValue;
			$LinkAttrs = &$this->transactiontype->LinkAttrs;
			$this->Cell_Rendered($this->transactiontype, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpFldCount = 0;
		$this->SubGrpFldCount = 0;
		$this->DtlFldCount = 0;
		if ($this->name->Visible) $this->DtlFldCount += 1;
		if ($this->user->Visible) $this->DtlFldCount += 1;
		if ($this->ordertype->Visible) $this->DtlFldCount += 1;
		if ($this->transactiondate->Visible) $this->DtlFldCount += 1;
		if ($this->ordernumber->Visible) $this->DtlFldCount += 1;
		if ($this->status->Visible) $this->DtlFldCount += 1;
		if ($this->platform->Visible) $this->DtlFldCount += 1;
		if ($this->transactiontype->Visible) $this->DtlFldCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage;
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_Sales_$parm"] = "";
		$_SESSION["rf_Sales_$parm"] = "";
		$_SESSION["rt_Sales_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->fields($parm);
		$fld->SelectionList = @$_SESSION["sel_Sales_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_Sales_$parm"];
		$fld->RangeTo = @$_SESSION["rt_Sales_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {

		/**
		* Set up default values for non Text filters
		*/

		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/

		/**
		* Set up default values for popup filters
		*/

		// Field name
		// $this->name->DefaultSelectionList = array("val1", "val2");
		// Field user
		// $this->user->DefaultSelectionList = array("val1", "val2");
		// Field ordertype
		// $this->ordertype->DefaultSelectionList = array("val1", "val2");
		// Field transactiondate
		// $this->transactiondate->DefaultSelectionList = array("val1", "val2");
		// Field ordernumber
		// $this->ordernumber->DefaultSelectionList = array("val1", "val2");
		// Field status
		// $this->status->DefaultSelectionList = array("val1", "val2");
		// Field platform
		// $this->platform->DefaultSelectionList = array("val1", "val2");
		// Field transactiontype
		// $this->transactiontype->DefaultSelectionList = array("val1", "val2");

	}

	// Check if filter applied
	function CheckFilter() {

		// Check name popup filter
		if (!ewr_MatchedArray($this->name->DefaultSelectionList, $this->name->SelectionList))
			return TRUE;

		// Check user popup filter
		if (!ewr_MatchedArray($this->user->DefaultSelectionList, $this->user->SelectionList))
			return TRUE;

		// Check ordertype popup filter
		if (!ewr_MatchedArray($this->ordertype->DefaultSelectionList, $this->ordertype->SelectionList))
			return TRUE;

		// Check transactiondate popup filter
		if (!ewr_MatchedArray($this->transactiondate->DefaultSelectionList, $this->transactiondate->SelectionList))
			return TRUE;

		// Check ordernumber popup filter
		if (!ewr_MatchedArray($this->ordernumber->DefaultSelectionList, $this->ordernumber->SelectionList))
			return TRUE;

		// Check status popup filter
		if (!ewr_MatchedArray($this->status->DefaultSelectionList, $this->status->SelectionList))
			return TRUE;

		// Check platform popup filter
		if (!ewr_MatchedArray($this->platform->DefaultSelectionList, $this->platform->SelectionList))
			return TRUE;

		// Check transactiontype popup filter
		if (!ewr_MatchedArray($this->transactiontype->DefaultSelectionList, $this->transactiontype->SelectionList))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList() {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field name
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->name->SelectionList))
			$sWrk = ewr_JoinArray($this->name->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->name->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field user
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->user->SelectionList))
			$sWrk = ewr_JoinArray($this->user->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->user->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ordertype
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->ordertype->SelectionList))
			$sWrk = ewr_JoinArray($this->ordertype->SelectionList, ", ", EWR_DATATYPE_NUMBER, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ordertype->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field transactiondate
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->transactiondate->SelectionList))
			$sWrk = ewr_JoinArray($this->transactiondate->SelectionList, ", ", EWR_DATATYPE_DATE, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->transactiondate->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field ordernumber
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->ordernumber->SelectionList))
			$sWrk = ewr_JoinArray($this->ordernumber->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->ordernumber->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field status
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->status->SelectionList))
			$sWrk = ewr_JoinArray($this->status->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->status->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field platform
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->platform->SelectionList))
			$sWrk = ewr_JoinArray($this->platform->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->platform->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field transactiontype
		$sExtWrk = "";
		$sWrk = "";
		if (is_array($this->transactiontype->SelectionList))
			$sWrk = ewr_JoinArray($this->transactiontype->SelectionList, ", ", EWR_DATATYPE_STRING, 0, $this->DBID);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->transactiontype->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "") {
			$sMessage = "<div class=\"ewDisplayTable\"" . $divstyle . "><div id=\"ewrFilterList\" class=\"alert alert-info\"" . $divdataclass . "><div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList . "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field name
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->name->SelectionList <> EWR_INIT_VALUE) ? $this->name->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_name\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field user
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->user->SelectionList <> EWR_INIT_VALUE) ? $this->user->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_user\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ordertype
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->ordertype->SelectionList <> EWR_INIT_VALUE) ? $this->ordertype->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ordertype\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field transactiondate
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->transactiondate->SelectionList <> EWR_INIT_VALUE) ? $this->transactiondate->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_transactiondate\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field ordernumber
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->ordernumber->SelectionList <> EWR_INIT_VALUE) ? $this->ordernumber->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_ordernumber\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field status
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->status->SelectionList <> EWR_INIT_VALUE) ? $this->status->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_status\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field platform
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->platform->SelectionList <> EWR_INIT_VALUE) ? $this->platform->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_platform\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field transactiontype
		$sWrk = "";
		if ($sWrk == "") {
			$sWrk = ($this->transactiontype->SelectionList <> EWR_INIT_VALUE) ? $this->transactiontype->SelectionList : "";
			if (is_array($sWrk))
				$sWrk = implode("||", $sWrk);
			if ($sWrk <> "")
				$sWrk = "\"sel_transactiontype\":\"" . ewr_JsEncode2($sWrk) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ewr_StripSlashes(@$_POST["filter"]), TRUE);

		// Field name
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_name", $filter)) {
			$sWrk = $filter["sel_name"];
			$sWrk = explode("||", $sWrk);
			$this->name->SelectionList = $sWrk;
			$_SESSION["sel_Sales_name"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field user
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_user", $filter)) {
			$sWrk = $filter["sel_user"];
			$sWrk = explode("||", $sWrk);
			$this->user->SelectionList = $sWrk;
			$_SESSION["sel_Sales_user"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field ordertype
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_ordertype", $filter)) {
			$sWrk = $filter["sel_ordertype"];
			$sWrk = explode("||", $sWrk);
			$this->ordertype->SelectionList = $sWrk;
			$_SESSION["sel_Sales_ordertype"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field transactiondate
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_transactiondate", $filter)) {
			$sWrk = $filter["sel_transactiondate"];
			$sWrk = explode("||", $sWrk);
			$this->transactiondate->SelectionList = $sWrk;
			$_SESSION["sel_Sales_transactiondate"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field ordernumber
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_ordernumber", $filter)) {
			$sWrk = $filter["sel_ordernumber"];
			$sWrk = explode("||", $sWrk);
			$this->ordernumber->SelectionList = $sWrk;
			$_SESSION["sel_Sales_ordernumber"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field status
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_status", $filter)) {
			$sWrk = $filter["sel_status"];
			$sWrk = explode("||", $sWrk);
			$this->status->SelectionList = $sWrk;
			$_SESSION["sel_Sales_status"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field platform
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_platform", $filter)) {
			$sWrk = $filter["sel_platform"];
			$sWrk = explode("||", $sWrk);
			$this->platform->SelectionList = $sWrk;
			$_SESSION["sel_Sales_platform"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}

		// Field transactiontype
		$bRestoreFilter = FALSE;
		if (array_key_exists("sel_transactiontype", $filter)) {
			$sWrk = $filter["sel_transactiontype"];
			$sWrk = explode("||", $sWrk);
			$this->transactiontype->SelectionList = $sWrk;
			$_SESSION["sel_Sales_transactiontype"] = $sWrk;
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
		}
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
			if (is_array($this->name->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->name, "`name`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->name, $sFilter, "popup");
				$this->name->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->user->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->user, "`user`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->user, $sFilter, "popup");
				$this->user->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->ordertype->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->ordertype, "`ordertype`", EWR_DATATYPE_NUMBER, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ordertype, $sFilter, "popup");
				$this->ordertype->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->transactiondate->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->transactiondate, "`transactiondate`", EWR_DATATYPE_DATE, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->transactiondate, $sFilter, "popup");
				$this->transactiondate->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->ordernumber->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->ordernumber, "`ordernumber`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->ordernumber, $sFilter, "popup");
				$this->ordernumber->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->status->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->status, "`status`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->status, $sFilter, "popup");
				$this->status->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->platform->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->platform, "`platform`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->platform, $sFilter, "popup");
				$this->platform->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
			if (is_array($this->transactiontype->SelectionList)) {
				$sFilter = ewr_FilterSQL($this->transactiontype, "`transactiontype`", EWR_DATATYPE_STRING, $this->DBID);

				// Call Page Filtering event
				$this->Page_Filtering($this->transactiontype, $sFilter, "popup");
				$this->transactiontype->CurrentFilter = $sFilter;
				ewr_AddFilter($sWrk, $sFilter);
			}
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort() {
		if ($this->DrillDown)
			return "";

		// Check for a resetsort command
		if (strlen(@$_GET["cmd"]) > 0) {
			$sCmd = @$_GET["cmd"];
			if ($sCmd == "resetsort") {
				$this->setOrderBy("");
				$this->setStartGroup(1);
				$this->name->setSort("");
				$this->user->setSort("");
				$this->ordertype->setSort("");
				$this->transactiondate->setSort("");
				$this->ordernumber->setSort("");
				$this->status->setSort("");
				$this->platform->setSort("");
				$this->transactiontype->setSort("");
			}

		// Check for an Order parameter
		} elseif (@$_GET["order"] <> "") {
			$this->CurrentOrder = ewr_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $ReportLanguage;
		$sContentType = @$_POST["contenttype"];
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];

		// Subject
		$sSubject = ewr_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ewr_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "")
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("EnterSenderEmail") . "</p>";
		if (!ewr_CheckEmail($sSender))
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("EnterProperSenderEmail") . "</p>";

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("EnterProperRecipientEmail") . "</p>";

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("EnterProperCcEmail") . "</p>";

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("EnterProperBccEmail") . "</p>";

		// Check email sent count
		$emailcount = ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return "<p class=\"text-error\">" . $ReportLanguage->Phrase("ExceedMaxEmailExport") . "</p>";
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_CleanEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return "<p class=\"text-success\">" . $ReportLanguage->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-error\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export to HTML
	function ExportHtml($html) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');
		//echo $html;

	} 

	// Export to WORD
	function ExportWord($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		echo $html;
	}

	// Export to EXCEL
	function ExportExcel($html) {
		global $gsExportFile;
		header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		echo $html;
	}

	// Export PDF
	function ExportPDF($html) {
		global $gsExportFile;
		include_once "dompdf061/dompdf_config.inc.php";
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		ob_end_clean();
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		ewr_DeleteTmpImages($html);
		$dompdf->stream($gsExportFile . ".pdf", array("Attachment" => 1)); // 0 to open in browser, 1 to download

//		exit();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($Sales_summary)) $Sales_summary = new crSales_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$Sales_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "phprptinc/header.php" ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var Sales_summary = new ewr_Page("Sales_summary");

// Page properties
Sales_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = Sales_summary.PageID;

// Extend page with Chart_Rendering function
Sales_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
Sales_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fSalessummary = new ewr_Form("fSalessummary");
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if ($Page->Export == "" && (!$Page->DrillDown || !$Page->DrillDownInPanel)) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
}
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "") { ?>
</div>
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- Left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fSalessummary" id="fSalessummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
</form>
<script type="text/javascript">
fSalessummary.Init();
fSalessummary.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export <> "pdf") { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "Salessmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->name->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="name"><div class="Sales_name"><span class="ewTableHeaderCaption"><?php echo $Page->name->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="name">
<?php if ($Page->SortUrl($Page->name) == "") { ?>
		<div class="ewTableHeaderBtn Sales_name">
			<span class="ewTableHeaderCaption"><?php echo $Page->name->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_name', false, '<?php echo $Page->name->RangeFrom; ?>', '<?php echo $Page->name->RangeTo; ?>');" id="x_name<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_name" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->name) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->name->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_name', false, '<?php echo $Page->name->RangeFrom; ?>', '<?php echo $Page->name->RangeTo; ?>');" id="x_name<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->user->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="user"><div class="Sales_user"><span class="ewTableHeaderCaption"><?php echo $Page->user->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="user">
<?php if ($Page->SortUrl($Page->user) == "") { ?>
		<div class="ewTableHeaderBtn Sales_user">
			<span class="ewTableHeaderCaption"><?php echo $Page->user->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_user', false, '<?php echo $Page->user->RangeFrom; ?>', '<?php echo $Page->user->RangeTo; ?>');" id="x_user<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_user" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->user) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->user->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->user->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->user->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_user', false, '<?php echo $Page->user->RangeFrom; ?>', '<?php echo $Page->user->RangeTo; ?>');" id="x_user<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ordertype->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ordertype"><div class="Sales_ordertype"><span class="ewTableHeaderCaption"><?php echo $Page->ordertype->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ordertype">
<?php if ($Page->SortUrl($Page->ordertype) == "") { ?>
		<div class="ewTableHeaderBtn Sales_ordertype">
			<span class="ewTableHeaderCaption"><?php echo $Page->ordertype->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_ordertype', false, '<?php echo $Page->ordertype->RangeFrom; ?>', '<?php echo $Page->ordertype->RangeTo; ?>');" id="x_ordertype<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_ordertype" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ordertype) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ordertype->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ordertype->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ordertype->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_ordertype', false, '<?php echo $Page->ordertype->RangeFrom; ?>', '<?php echo $Page->ordertype->RangeTo; ?>');" id="x_ordertype<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->transactiondate->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="transactiondate"><div class="Sales_transactiondate"><span class="ewTableHeaderCaption"><?php echo $Page->transactiondate->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="transactiondate">
<?php if ($Page->SortUrl($Page->transactiondate) == "") { ?>
		<div class="ewTableHeaderBtn Sales_transactiondate">
			<span class="ewTableHeaderCaption"><?php echo $Page->transactiondate->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_transactiondate', true, '<?php echo $Page->transactiondate->RangeFrom; ?>', '<?php echo $Page->transactiondate->RangeTo; ?>');" id="x_transactiondate<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_transactiondate" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->transactiondate) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->transactiondate->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->transactiondate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->transactiondate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_transactiondate', true, '<?php echo $Page->transactiondate->RangeFrom; ?>', '<?php echo $Page->transactiondate->RangeTo; ?>');" id="x_transactiondate<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->ordernumber->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="ordernumber"><div class="Sales_ordernumber"><span class="ewTableHeaderCaption"><?php echo $Page->ordernumber->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="ordernumber">
<?php if ($Page->SortUrl($Page->ordernumber) == "") { ?>
		<div class="ewTableHeaderBtn Sales_ordernumber">
			<span class="ewTableHeaderCaption"><?php echo $Page->ordernumber->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_ordernumber', false, '<?php echo $Page->ordernumber->RangeFrom; ?>', '<?php echo $Page->ordernumber->RangeTo; ?>');" id="x_ordernumber<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_ordernumber" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->ordernumber) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->ordernumber->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->ordernumber->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->ordernumber->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_ordernumber', false, '<?php echo $Page->ordernumber->RangeFrom; ?>', '<?php echo $Page->ordernumber->RangeTo; ?>');" id="x_ordernumber<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="status"><div class="Sales_status"><span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="status">
<?php if ($Page->SortUrl($Page->status) == "") { ?>
		<div class="ewTableHeaderBtn Sales_status">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_status', false, '<?php echo $Page->status->RangeFrom; ?>', '<?php echo $Page->status->RangeTo; ?>');" id="x_status<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_status" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->status) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->status->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_status', false, '<?php echo $Page->status->RangeFrom; ?>', '<?php echo $Page->status->RangeTo; ?>');" id="x_status<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->platform->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="platform"><div class="Sales_platform"><span class="ewTableHeaderCaption"><?php echo $Page->platform->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="platform">
<?php if ($Page->SortUrl($Page->platform) == "") { ?>
		<div class="ewTableHeaderBtn Sales_platform">
			<span class="ewTableHeaderCaption"><?php echo $Page->platform->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_platform', false, '<?php echo $Page->platform->RangeFrom; ?>', '<?php echo $Page->platform->RangeTo; ?>');" id="x_platform<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_platform" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->platform) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->platform->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->platform->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->platform->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_platform', false, '<?php echo $Page->platform->RangeFrom; ?>', '<?php echo $Page->platform->RangeTo; ?>');" id="x_platform<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->transactiontype->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="transactiontype"><div class="Sales_transactiontype"><span class="ewTableHeaderCaption"><?php echo $Page->transactiontype->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="transactiontype">
<?php if ($Page->SortUrl($Page->transactiontype) == "") { ?>
		<div class="ewTableHeaderBtn Sales_transactiontype">
			<span class="ewTableHeaderCaption"><?php echo $Page->transactiontype->FldCaption() ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_transactiontype', false, '<?php echo $Page->transactiontype->RangeFrom; ?>', '<?php echo $Page->transactiontype->RangeTo; ?>');" id="x_transactiontype<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer Sales_transactiontype" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->transactiontype) ?>',0);">
			<span class="ewTableHeaderCaption"><?php echo $Page->transactiontype->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->transactiontype->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->transactiontype->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
			<a class="ewTableHeaderPopup" title="<?php echo $ReportLanguage->Phrase("Filter"); ?>" onclick="ewr_ShowPopup.call(this, event, 'Sales_transactiontype', false, '<?php echo $Page->transactiontype->RangeFrom; ?>', '<?php echo $Page->transactiontype->RangeTo; ?>');" id="x_transactiontype<?php echo $Page->Cnt[0][0]; ?>"><span class="icon-filter"></span></a>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}
	$Page->RecCount++;
	$Page->RecIndex++;

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->name->Visible) { ?>
		<td data-field="name"<?php echo $Page->name->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_name"<?php echo $Page->name->ViewAttributes() ?>><?php echo $Page->name->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->user->Visible) { ?>
		<td data-field="user"<?php echo $Page->user->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_user"<?php echo $Page->user->ViewAttributes() ?>><?php echo $Page->user->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ordertype->Visible) { ?>
		<td data-field="ordertype"<?php echo $Page->ordertype->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_ordertype"<?php echo $Page->ordertype->ViewAttributes() ?>><?php echo $Page->ordertype->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->transactiondate->Visible) { ?>
		<td data-field="transactiondate"<?php echo $Page->transactiondate->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_transactiondate"<?php echo $Page->transactiondate->ViewAttributes() ?>><?php echo $Page->transactiondate->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->ordernumber->Visible) { ?>
		<td data-field="ordernumber"<?php echo $Page->ordernumber->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_ordernumber"<?php echo $Page->ordernumber->ViewAttributes() ?>><?php echo $Page->ordernumber->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
		<td data-field="status"<?php echo $Page->status->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_status"<?php echo $Page->status->ViewAttributes() ?>><?php echo $Page->status->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->platform->Visible) { ?>
		<td data-field="platform"<?php echo $Page->platform->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_platform"<?php echo $Page->platform->ViewAttributes() ?>><?php echo $Page->platform->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->transactiontype->Visible) { ?>
		<td data-field="transactiontype"<?php echo $Page->transactiontype->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_Sales_transactiontype"<?php echo $Page->transactiontype->ViewAttributes() ?>><?php echo $Page->transactiontype->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>><td colspan="<?php echo ($Page->GrpFldCount + $Page->DtlFldCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php
	$Page->ResetAttrs();
	$Page->ordertype->Count = $Page->GrandCnt[3];
	$Page->ordertype->SumValue = $Page->GrandSmry[3]; // Load SUM
	$Page->RowTotalSubType = EWR_ROWTOTAL_SUM;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->name->Visible) { ?>
		<td data-field="name"<?php echo $Page->name->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->user->Visible) { ?>
		<td data-field="user"<?php echo $Page->user->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->ordertype->Visible) { ?>
		<td data-field="ordertype"<?php echo $Page->ordertype->CellAttributes() ?>><span class="ewAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></span>
<span data-class="tpts_Sales_ordertype"<?php echo $Page->ordertype->ViewAttributes() ?>><?php echo $Page->ordertype->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->transactiondate->Visible) { ?>
		<td data-field="transactiondate"<?php echo $Page->transactiondate->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->ordernumber->Visible) { ?>
		<td data-field="ordernumber"<?php echo $Page->ordernumber->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->status->Visible) { ?>
		<td data-field="status"<?php echo $Page->status->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->platform->Visible) { ?>
		<td data-field="platform"<?php echo $Page->platform->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->transactiontype->Visible) { ?>
		<td data-field="transactiontype"<?php echo $Page->transactiontype->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
	</tr>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && TRUE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "Salessmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || TRUE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "Salessmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container - report (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
<?php } ?>
	<!-- Right slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
<?php } ?>
	<!-- Bottom slot -->
<?php if ($Page->Export == "") { ?>
	</div>
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
<?php } ?>
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
