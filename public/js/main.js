 /**
 * File name: main.js
 *
 * This file is part of PROJECKLIST
 *
 * Copyright (c) 2015 Daniel Racine
 * You should have received a copy of the MIT License
 * along with PROJECKLIST. If not, see <https://en.wikipedia.org/wiki/MIT_License>.
 */




















/*=======================================================================================
	GLOBAL VARIABLES DECLARATION SECTION
=======================================================================================*/


/* 
 * GLOBAL VARIABLES
 * 
 */
 	// Global variables on load...
 	var vh, vw, scrollPostion;
	var heightTrigger = 0.4; // define % of animPosition variable
	var animPosition; // set % height to trigger animation from vh

	// variables to know scroll position before/after animPosition
	var ps = 1 - heightTrigger + 0.20;
	var pe = 1 - heightTrigger - 0.20;
	var st, nd;

	// mQuery viewport width size parameters
	var vwDesktop = 1440;
	var vwLaptop = 1024;
	var vwTablet = 768;
	var vwPhablet = 600;

	// Global variables used with addition/removal of fielsets
	var confirmBoxType = "confirm";
	var removeClicked; // Passed in selector from .js-fieldset-removal buttons
	var fieldsetCount,
		fieldsetName,
		minFieldsetAllowed,
		maxFieldsetAllowed,
		modalDecision,
		bAlert,
		bAccept,
		bDecline;

	// Global variables used with email validation functions
	var validationField, validationValue, emailMatch, thisValue, parentSection, thisSelector, thisHint;

 	// Global variables to use with the replaceInfo() function
	var to_fn, to_ln, to_ph, to_email, to_emailValid, fr_fn, fr_ln, fr_ph, fr_email, fr_emailValid;
	var fromFields = [];
	var toFields = [];

	// Global variables use with the Other/Details toggled textarea
	var theTextArea;




















/*=======================================================================================
	FUNCTION DECLARATION SECTION
=======================================================================================*/

/* 
 * FUNCTION
 * fromToClass()
 * 
 * switch from one specific .class to another specific .class on the specified selector.
 * 
 * takes 3 parameters:
 * selector -> object, pass the targeted selector
 * prev -> string, pass the class name to be switch
 * next -> string, pass the class name to switch to
 * 
 */
	function fromToClass(selector, prev, next) {

		if (selector.hasClass(prev))
		{
			selector.addClass(next);
			selector.removeClass(prev);
		}

	} // fromToClass() END




/* 
 * FUNCTION
 * switchClass()
 * 
 * switch between two classes on the specified selector.
 * 
 * takes 3 parameters:
 * selector -> object, pass the targeted selector
 * prev -> string, pass the class name to be switch
 * next -> string, pass the class name to switch to
 * 
 */
	function switchClass(selector, prev, next) {

		if (selector.hasClass(prev))
		{
			selector.addClass(next);
			selector.removeClass(prev);
		}
		else if (selector.hasClass(next))
		{
			selector.addClass(prev);
			selector.removeClass(next);
		}

	} // switchClass() END




/* 
 * FUNCTION
 * setDialog()
 * 
 * set Dialog Box string content
 * 
 * takes 1 parameters:
 * name -> sring, pass the name of dialog box to display
 * 
 */
	function setDialog(name) {

		$("[class*='js-dialog-']").each( function() {
			fromToClass($(this), "is-visible", "is-hidden");
		});

		fromToClass($(".js-dialog-"+name), "is-hidden", "is-visible");

	} // setDialog() END




/* 
 * FUNCTION
 * setVariables()
 * 
 * Set global variables value used in this function collections
 * 
 * takes 1 parameter:
 * selector -> object, pass the targeted selector
 * 
 */
	function setVariables(selector) {

		var thisSelector = selector;
		var fieldsets = thisSelector.closest("[id*='f-main-fieldset-']").find("[class*='js-fieldset-']");
		var fieldsetName = thisSelector.closest("[id*='f-main-fieldset-']").attr('id');

		fieldsetCount = 0;
		fieldsets.each( function() {
			if ($(this).hasClass("is-visible")) {
				fieldsetCount++;
			};
		});

		// Set variables used for each sections
		if (/\bcontact\b/ig.test(fieldsetName))
		{
			// Define the maximum number of additonal fielsets for this section
			maxFieldsetAllowed = 2;
			minFieldsetAllowed = 1;

			// Define the text in the removal modal box
			setDialog("contact");
		}
		else if (/\bhours\b/ig.test(fieldsetName))
		{	
			// Define the maximum number of additonal fielsets for this section
			maxFieldsetAllowed = 7;
			minFieldsetAllowed = 1;

			// Define the text in the removal modal box
			setDialog("hours");
		}
		else if (/\bcompetitors\b/ig.test(fieldsetName))
		{	
			// Define the maximum number of additonal fielsets for this section
			maxFieldsetAllowed = 6;
			minFieldsetAllowed = 1;

			// Define the text in the removal modal box
			setDialog("competitors");
		}
		// This block impacts 2 sections
		else if ( /\blike\b/ig.test(fieldsetName) || /\bdislike\b/ig.test(fieldsetName) )
		{	
			// Define the maximum number of additonal fielsets for this section
			maxFieldsetAllowed = 4;
			minFieldsetAllowed = 2;

			// Define the text in the removal modal box
			setDialog("weblist");
		}
		// This block impacts 2 sections
		else if (/\bdomain\b/ig.test(fieldsetName))
		{	
			// Define the maximum number of additonal fielsets for this section
			maxFieldsetAllowed = 6;
			minFieldsetAllowed = 1;

			// Define the text in the removal modal box
			setDialog("domain");
		}

	} // setVariables() END




/* 
 * FUNCTION
 * btnCheck()
 * 
 * Manage the show / hide button visibilty when fieldsets are added / removed
 * 
 * takes 1 parameter:
 * selector -> object, pass the targeted selector
 * 
 */
	function btnCheck(selector) {

		var thisSelector = selector;
		var show_btn = thisSelector.closest(".js-f-main-fieldset").find(".js-btn-show");
		var hide_btn = thisSelector.closest(".js-f-main-fieldset").find(".js-btn-hide");

		setVariables(thisSelector);

		// Block of code that manage show/hide buttons visibilty when fieldset are added and removed.
		if (fieldsetCount == minFieldsetAllowed) 
		{
			fromToClass(show_btn, "is-deactivated", "is-activated");
			fromToClass(hide_btn, "is-activated", "is-deactivated");

			fromToClass(show_btn, "is-hidden", "is-visible");
			fromToClass(hide_btn, "is-visible", "is-hidden");

			fromToClass(show_btn, "is-disabled", "is-active");
			fromToClass(hide_btn, "is-active", "is-disabled");
		}
		else if (fieldsetCount == maxFieldsetAllowed)
		{
			fromToClass(show_btn, "is-activated", "is-deactivated");
			fromToClass(hide_btn, "is-deactivated", "is-activated");

			fromToClass(show_btn, "is-visible", "is-hidden");
			fromToClass(hide_btn, "is-hidden", "is-visible");

			fromToClass(show_btn, "is-active", "is-disabled");
			fromToClass(hide_btn, "is-disabled", "is-active");
		}
		else
		{
			fromToClass(show_btn, "is-deactivated", "is-activated");
			fromToClass(hide_btn, "is-deactivated", "is-activated");

			fromToClass(show_btn, "is-hidden", "is-visible");
			fromToClass(hide_btn, "is-hidden", "is-visible");

			fromToClass(show_btn, "is-disabled", "is-active");
			fromToClass(hide_btn, "is-disabled", "is-active");
		}

	} // btnCheck() END




/* 
 * FUNCTION
 * removeField()
 * 
 * Remove a fieldset
 * 
 * takes no parameter.
 * 
 */
	function removeField() {

		var thisSelector = removeClicked;
		var show_btn = thisSelector.closest(".js-f-main-fieldset").find(".js-btn-show");
		var hide_btn = thisSelector.closest(".js-f-main-fieldset").find(".js-btn-hide");

		// Remove the fieldset
		fromToClass(thisSelector, "is-visible", "is-hidden");
		btnCheck(thisSelector);

		// Value reset for input/textarea fields
		var first = 0;
		thisSelector.find("input, textarea").each( function() {
			$(this).val("");

			if ($(this).attr("type") == "email") 
			{
				if (first == 0) 
				{
					emailVerification($(this));
					first++;
				}
			}
		});

		// Reset for hours of operations
		if (thisSelector.find("select").first().val() != "") {
			thisSelector.find("select").first().val("");
		}

	} // removeField() END




/* 
 * FUNCTION
 * setVariables()
 * 
 * Modal box confirmation
 * 
 * takes 1 or no parameter:
 * selector -> object, pass the decision selector of the confirmation box
 * no parameter -> this open/close the modal box
 * 
 * 
 * ref: http://blog.raventools.com/create-a-modal-dialog-using-css-and-javascript/
 */
	function overlay(btnSelector) {

		el = document.getElementById("overlay");
		el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
		
		elb = document.getElementById("f-container");
		elb.style.webkitFilter = (elb.style.webkitFilter == "blur(3px)") ? "blur(0px)" : "blur(3px)";
		// console.log(window.getComputedStyle(elb, null));

		// var elf = $(".f-container");
		// switchClass( elf, "is-clear", "is-blur" );

		var elc = $("#overlay-container");
		switchClass( elc, "spring-in", "spring-out" );

		if(typeof btnSelector === 'undefined')
		{
			return;
		}
		else
		{
			var value = btnSelector;
			value = value.getAttribute("value");

			if (value == true)
			{

				// setVariables(btnSelector);
				
				if (modalDecision == "sameas")
				{
					replaceInfo();
				}
				else if (modalDecision == "fields")
				{
					removeField();
				}
				else if (modalDecision == "alert")
				{
					// Alert only -> Do nothing
				}
				return;
			}
			else
			{
				if (modalDecision == "sameas")
				{
					$("#f-sameas-primary").attr('checked', false);
				}
				return;
			}
		}

	} // overlay() END




/* 
 * Function that adds and remove fieldsets in the form
 * 
 * wrapper -> .js-f-main-fieldset
 * wrapped element -> <fieldset></fieldset>
 * fieldsets -> .js-fieldset-##
 * add button -> .js-fieldset-add
 * remove button -> .js-fieldset-remove
 * 
 * Setting in function
 * -> Add "else if" block to set variables
 * -> Make sure to adjust button management addition and removal
 * 
 */
	function addFieldsets(selector) {

		var btn = selector;
		// setVariables(selector);

		if (btn.hasClass("js-btn-show"))
		{
			var fieldsList = btn.closest(".js-f-main-fieldset").find("[class*='js-fieldset-']");

			fieldsList.each( function() {
				var thisFieldset = $(this);
				if (thisFieldset.hasClass("is-hidden"))
				{
					fromToClass(thisFieldset, "is-hidden", "is-visible");
					btnCheck(thisFieldset);
					return false;
				};
			});
		}
		else if (btn.hasClass("js-btn-hide"))
		{
			var fieldsList = btn.closest(".js-f-main-fieldset").find("[class*='js-fieldset-']");

			var r = 0;

			$(fieldsList.get().reverse()).each( function() {
				var thisFieldset = $(this);
				var thisFieldsetText = thisFieldset.find("input:not([type='radio'],[type='checkbox'])");

				// Get the class name -# in a variable at the begingin of the function
				if (thisFieldset.hasClass("is-visible") && !thisFieldset.hasClass("js-fieldset-1") && !thisFieldset.hasClass("js-fieldset-lock"))
				{
					removeClicked = thisFieldset;

					if (thisFieldset.find(".js-hours-block").length > 0)
					{
						if (thisFieldset.find(".js-hours-block select option").filter(':selected').val().length != 0)
						{
							modalDecision = "fields";
							overlay();
						}
						else
						{
							removeField();
						}
						return false;
					}
					else if (thisFieldsetText.length > 0)
					{
						if (thisFieldsetText.val().length != 0)
						{
							// Activate the removal modal box
							modalDecision = "fields";
							overlay();
							return false;
						}
						else
						{
							removeField();
							return false;
						}
					}
					else
					{
						// Activate the removal modal box
						modalDecision = "fields";
						overlay();
						return false;
					}
				}
			});

		}

	} // addFieldsets() END




/* 
 * FUNCTION
 * eMatch( selectors , toClass )
 * 
 * Make sure the class "match", "no-match" are present appropriate styling of email input fields
 * 
 * 2 parameters:
 * selectors -> object array of all selectors
 * toClass -> CSS class name to "switch" to
 * 
 */
	function eMatch(selectors, toClass) {

		var fromClass = (toClass == "js-match") ? "js-no-match" : "js-match";
		selectors.find("input").each( function() {
			var thisInput = $(this);
			fromToClass(thisInput, fromClass, toClass);

			if (!thisInput.hasClass(toClass))
			{
				thisInput.addClass(toClass);
			}
		});

	} // eMatch() END




/* 
 * FUNCTION
 * eCheck( bool )
 * 
 * Trigger appropriate email styling -> Class and validation hint status
 * 
 * 1 parameter:
 * bool -> true/false if email value verif/valid matches
 * 
 */
	function eCheck(bool) {

		if (bool)
		{
			eMatch(parentSection, "js-match");
			fromToClass(thisHint, "js-hint-no-match", "js-hint-match");
			fromToClass(thisHint, "fa-exclamation-triangle", "fa-check");
		}
		else
		{
			eMatch(parentSection, "js-no-match");
			fromToClass(thisHint, "js-hint-match", "js-hint-no-match");
			fromToClass(thisHint, "fa-check", "fa-exclamation-triangle");
		}

	} // eCheck() END




/* 
 * FUNCTION
 * emailVerification( selector )
 * 
 * Manage behavior of the email verification field
 * 
 * takes 1 parameter:
 * selector -> object, pass the targeted email input field selector
 * 
 */
 	function emailVerification( selector ) {

		thisSelector = selector;
		parentSection = thisSelector.parents(".js-fieldset-email");
		thisValue = thisSelector.val();
		thisHint = parentSection.find(".fa");

		// When you select $('#profileform') you get a collection of nodes, to access actual DOM properties you must select the first one.
		// http://stackoverflow.com/questions/7386817/html5-form-checkvalidity-method-not-found
		emailCheck = thisSelector[0].checkValidity();
		emailContent = thisSelector.val();

		validationField = parentSection.find(".js-email-validation");
		validationValue = validationField.find("input");
		emailMatch = thisValue === validationValue.val();

		//Check email validity && Check the email field is not empty && Check if validation field value empty
		if (emailCheck && thisValue.length != 0 && validationValue.val().length == 0)
		{
			// Once require validation, make sure the class "match", "no-match" are present appropriate styling
			eMatch(parentSection, "js-no-match");
			if (thisValue.length >= 6)
			{
				fromToClass(validationField, "is-hidden", "is-visible");
			}
		}
		else if (emailCheck && thisValue != 0 && validationValue.val().length > 0)
		{
			eCheck(emailMatch);
			if (thisValue.length >= 6)
			{
				fromToClass(validationField, "is-hidden", "is-visible");
			}
		}
		else
		{
			fromToClass(validationField, "is-visible", "is-hidden");

			// Make sure the validator field is empty when the verification field is empty as well
			if (thisValue.length < 6)
			{
				validationValue.val("");
			}

			// Once validated, make sure the class "match", "no-match" are present appropriate styling
			parentSection.find("input").each( function() {
				var thisInput = $(this);

				if (thisInput.hasClass("js-no-match"))
				{	
					thisInput.removeClass("js-no-match");
				}

				if (thisInput.hasClass("error"))
				{	
					thisInput.removeClass("error");
				}

			});

		}

 	} // emailVerification() END




/* 
 * FUNCTION
 * emailValidation( selector )
 * 
 * Manage the show / hide button visibilty when fieldsets are added / removed
 * 
 * takes 1 parameter:
 * selector -> object, pass the targeted selector
 * 
 */
 	function emailValidation( selector ) {

		thisSelector = selector;
		parentSection = thisSelector.parents(".js-fieldset-email");
		thisValue = thisSelector.val();
		thisHint = parentSection.find(".fa");
		verificationField = parentSection.find(".js-email-verification");
		verificationValue = verificationField.find("input").val();

		// When you select $('#profileform') you get a collection of nodes, to access actual DOM properties you must select the first one.
		// http://stackoverflow.com/questions/7386817/html5-form-checkvalidity-method-not-found
		emailCheck = thisSelector[0].checkValidity();
		emailContent = thisSelector.val();

		emailMatch = thisValue === verificationValue;
		eCheck(emailMatch);

 	} // emailValidation() END




/* 
 * FUNCTION
 * replaceInfo()
 * 
 * Manage the "same as primary contact" functionality in the billing area
 * 
 * No parameters
 * 
 */
 	function replaceInfo() {

        to_fn.val(fr_fn.val());to_fn.focusout();
        to_ln.val(fr_ln.val());to_ln.focusout();
        to_ph.val(fr_ph.val());to_ph.focusout();
        to_email.val(fr_email.val());to_email.focusout();

        if (fr_email.val() != "")
        {
        	emailVerification(to_email);

        	if (fr_emailValid.val() != "")
        	{
        		to_emailValid.val(fr_emailValid.val());to_emailValid.focusout();
    			emailValidation(to_emailValid);
        	}
        }

        to_fn.parents(".js-f-main-fieldset").find("input").each( function() {
        	if ($(this).val() == "")
        	{
	        	$(this).focus();
	        	return false;
        	}
        });

 	} // replaceInfo() END




/* 
 * FUNCTION
 * btnVisibility( type )
 * 
 * Manage button behavior of modal box depending of initiation
 * 
 * 1 parameter:
 * type -> The type of modal box ("confirm" or "alert")
 * 
 */
	function btnVisibility( type ) {

		var btnType = type;

		if (btnType === "confirm")
		{
    		fromToClass(bAlert, "is-visible", "is-hidden");
    		fromToClass(bAccept, "is-hidden", "is-visible");
    		fromToClass(bDecline, "is-hidden", "is-visible");
		}
		else if (btnType === "alert")
		{
			fromToClass(bAlert, "is-hidden", "is-visible");
    		fromToClass(bAccept, "is-visible", "is-hidden");
    		fromToClass(bDecline, "is-visible", "is-hidden");
		}

	} // btnVisibility() END




/* 
 * FUNCTION
 * toggleArea( selector )
 * 
 * Expand/Collapse radio/checkbox choice selections sub-selections
 * 
 * 1 parameter:
 * selector -> The selector which the status change checked/uncheck
 * 
 */
	function toggleArea( selector ) {
		var theSwitch = selector;
		var theAttribute = theSwitch.attr("type");
		var theArea, theOtherAreas;
			theArea = theSwitch.parent().find(".js-toggle-area");

		// Clear/reset input and textarea on toggle off
    	function clearSubFields( selectors ) {

    		selectors.each( function() {
    			$(this).attr('checked', false);
    			if ($(this).hasClass("js-other-details"))
    			{
    				toggleArea($(this));
    			}
    		});

    	}

		// Behavior of Other/Detaisl checkbox -> expand/collapse the textarea
		if (theAttribute == "radio" && theSwitch.hasClass("js-other-details"))
		{
			theTextArea = theSwitch.parent().find("textarea");
			theOtherAreas = theSwitch.parent().siblings("div").find("textarea");

	    	theOtherAreas.each( function() {
	    		fromToClass($(this), "is-visible", "is-hidden");
		    	if ($(this).val() != 0)
		    	{
		    		$(this).val("");
		    	}
	    	});

		    if(theSwitch.is(':checked') && theTextArea.length != 0)
		    {
		    	fromToClass(theTextArea, "is-hidden", "is-visible");
		    	theTextArea.focus();
		    }
		}
		// Behavior of sub section selections after selecting radio buttons
		else if (theAttribute == "radio") 
		{
			theOtherAreas = theSwitch.parent().siblings("div").find(".js-toggle-area");

		    if (theSwitch.is(':checked') && theArea.length != 0)
		    {
		    	theOtherAreas.each( function() {
		    		fromToClass($(this), "is-visible", "is-hidden");
		    		// clearSubFields($(this).find("input"));
		    		$(this).find("input, textarea").each( function() {
						clearSubFields($(this));
		    		});
		    	});

		    	fromToClass(theArea, "is-hidden", "is-visible");
		    	
		    	if (theArea.find(".m-odtext")) {
		    		theArea.find(".m-odtext").first().find("textarea").focus();
		    	}

		    }
		    else (theSwitch.is(':checked') && theOtherAreas.length != 0)
		    {
		    	theOtherAreas.each( function() {
		    		fromToClass($(this), "is-visible", "is-hidden");
		    		// clearSubFields($(this).find("input"));
		    		$(this).find("input, textarea").each( function() {
						clearSubFields($(this));
		    		});
		    	});
		    }
		}
		// Behavior of sub section selections after checking checkbox
		else if (theAttribute == "checkbox" && theArea.length != 0)
		{
		    if (theSwitch.is(':checked'))
		    {
		    	fromToClass(theArea, "is-hidden", "is-visible");
		    }
		    else 
		    {
		    	fromToClass(theArea, "is-visible", "is-hidden");
		    	clearSubFields(theArea.find("input"));
		    }
		}
		// Behavior of Other/Detaisl checkbox -> expand/collapse the textarea
		else if (theAttribute == "checkbox" && theSwitch.hasClass("js-other-details"))
		{
			theTextArea = theSwitch.parent().find("textarea");

		    if(theSwitch.is(':checked'))
		    {
		    	fromToClass(theTextArea, "is-hidden", "is-visible");
		    	theTextArea.focus();
		    }
		    else 
		    {
		    	fromToClass(theTextArea, "is-visible", "is-hidden");

		    	if (theTextArea.val() != 0)
		    	{
		    		theTextArea.val("");
		    	}
		    }
		}
		// Behavior of Hours of Operation checbox. (select all closed)
		else if (theAttribute == "checkbox" && theSwitch.hasClass("js-hours-closed"))
		{
			var dropdownFields = theSwitch.parents("[class*='js-fieldset-']").find("select");

		    if(theSwitch.is(':checked'))
		    {
		    	// console.log("I AM CHECKED");
		    	dropdownFields.each( function() {

		    		if ($(this).find(".js-opt-closed").length != 0)
		    		{
		    			$(this).find(".js-opt-closed").prop("selected", true);
		    			$(this).find(".js-opt-closed").change();
		    		}

		    	});
		    }
		}

	} // toggleArea() END




















/*=======================================================================================
	PURE JS SCRIPT
=======================================================================================*/

// Get window width/height
function setWindowDimension() {
	// the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
	if (typeof window.innerWidth != 'undefined')
	{
		vw = window.innerWidth;
		vh = window.innerHeight;
		// console.log(vw + "x" + vh);
	}
}




// Set viewport scalabilty depending of screen width -> Block user zoom capabilities on mobile/tablet
function setViewportScale() {
	if (vw < 1050)
	{
 		var viewport;
		viewport = document.getElementsByName("viewport");
		viewport[0].setAttribute("content", "width=device-width, initial-scale=1, maximum-scale=1");
	}
}




// Add class to the body element depending of screen sidth ( matching CSS media queries)
function jsMediaQ(){
 	var body = document.getElementsByTagName("body")[0];

 	// Get theme class name on body el.
 	var reTheme = /(th-dark|th-light)/i;
	var themeName = reTheme.exec(body.className)[0];

	function setQuery(name) {
		body.className=themeName+' '+name;
	}

    if (vw >= vwDesktop)
    {
		setQuery("js-desktop");
    }
    else if (vw >= vwLaptop)
    {
		setQuery("js-laptop");
    }
    else if (vw >= vwTablet)
    {
		setQuery("js-tablet");
    }
    else if (vw >= vwPhablet)
    {
		setQuery("js-phablet")
    } 
    else
    {
		setQuery("js-mobile");
    }

} 




// Page load/reload/resize initializer
function viewportIni() {

	setWindowDimension();
	setViewportScale();
	jsMediaQ();
    animPosition = vh * heightTrigger;

	// scroll listening postion
	st = vh * ps;
	nd = vh * pe;
}




// Window resize initialiser
(function(){

	viewportIni()

	// Older version of IE -> < 9
	if(window.attachEvent)
	{
	    window.attachEvent('onresize', function() {
			viewportIni();
	    });
	}
	else if(window.addEventListener)
	{
	    window.addEventListener('resize', function() {
			viewportIni();
	    }, true);
	}
	// Event listener not supported
	else
	{
	    viewportIni();
	}

})();




/*
 * jQuery Autogrow Text Area
 * version 1.0
 * It automatically adjusts the height on text area.
 *
 * Written by Jerry Luk jerry@presdo.com
 *
 * Based on Chrys Bader's Auto Expanding Text area www.chrysbader.com
 * and Craig Buckler's TextAreaExpander  http://www.sitepoint.com/blogs/2009/07/29/build-auto-expanding-textarea-1/
 *
 * Licensed under MIT license.
 */
 
(function($) {
  $.fn.autogrow = function(options) {
    var defaults = {
      expandTolerance: 1,
      heightKeeperFunction: null
    };
    options = $.extend(defaults, options);
    
    // IE and Opera should never set a textarea height of 0px
    // (DEPRECATED) -> var hCheck = !($.browser.msie || $.browser.opera);
    
    function resize(e) {
      var $e            = $(e.target || e), // event or element
          contentLength = $e.val().length,
          elementWidth  = $e.innerWidth();
      if ($e.is(":hidden")) {
        // Do not do anything if the element is hidden as we cannot determine the height correctly
        return $e;
      }
      if (contentLength != $e.data("autogrow-length") || elementWidth != $e.data("autogrow-width")) {
        
        // For non-IE and Opera browser, it requires setting the height to 0px to compute the right height
        if (/* (DEPRECATED) -> hCheck && */(contentLength < $e.data("autogrow-length") || 
          elementWidth != $e.data("autogrow-width"))) {
          if ($.isFunction(options.heightKeeperFunction)) {
            (options.heightKeeperFunction($e)).height((options.heightKeeperFunction($e)).height());
          }
          $e.css("height", "0px");
        }
        
        var height = Math.max($e.data("autogrow-min"), Math.ceil(Math.min(
          $e.prop("scrollHeight") + options.expandTolerance * $e.data("autogrow-line-height"), 
          $e.data("autogrow-max"))));

        $e.css("overflow", ($e.prop("scrollHeight") > height ? "auto" : "hidden"));
        $e.css("height", height + "px");
        if ($.isFunction(options.heightKeeperFunction)) {
          (options.heightKeeperFunction($e)).css({ height: 'auto' });
        }
      }
      
      return $e;
    };
    
    function parseNumericValue(v) {
      var n = parseInt(v, 10);
      return isNaN(n) ? null : n;
    };
    
    function initElement($e) {
      $e.data("autogrow-min", options.minHeight || parseNumericValue($e.css('min-height')) || 0);
      $e.data("autogrow-max", options.maxHeight || parseNumericValue($e.css('max-height')) || 99999);
      $e.data("autogrow-line-height", options.lineHeight || parseNumericValue($e.css('line-height')));
      resize($e);
    };
    
    this.each(function() {
      var $this = $(this);
            
      if (!$this.data("autogrow-initialized")) {
        $this.css("padding-top", 20).css("padding-bottom", 20); // Manually added padding to textareas
        $this.bind("keyup", resize).bind("focus", resize);
        $this.data("autogrow-initialized", true);
      }
      
      initElement($this);
      // Sometimes the CSS attributes are not yet there so the above computation might be wrong
      // 100ms delay will do the job
      setTimeout(function() { initElement($this); }, 100);
    });
    
    return this;
  };
})(jQuery);





















/*=======================================================================================
	DOCUMENT READY SCRIPT
=======================================================================================*/

$( document ).ready(function() {




	// Set Global Variables for modal box and 

	// Modal box buttons
	bAlert = $(".m-alert");
	bAccept = $(".m-accept")
	bDecline = $(".m-decline")

	// Input tags variable list for "same as primary contact" feature
	fr_fn = $("#f-contact-firstname-1");
	fr_ln = $("#f-contact-lastname-1");
	fr_ph = $("#f-contact-phone-1");
	fr_email = $("#f-contact-email-verification-1");
	fr_emailValid = $("#f-contact-email-validator-1");
	to_fn = $("#f-billing-fn");
	to_ln = $("#f-billing-ln");
	to_ph = $("#f-billing-phone");
	to_email = $("#f-billing-email-verification");
	to_emailValid = $("#f-billing-email-validator");
	fromFields = [ fr_fn , fr_ln , fr_ph , fr_email, fr_emailValid ];
	toFields = [ to_fn , to_ln , to_ph , to_email, to_emailValid ];




	// Define Language menu button state behaviors touch/no-touch
	$(".m-menu-li-drop").click( function() {

		var thisMenu = $(this);
		var isTouchDevice = Modernizr.touch;

		function isLangHover(selector) {
			var thisMenu = selector;

			if (thisMenu.is(":hover") && !isTouchDevice) 
			{
				thisMenu.mouseleave( function() {
					var thisMenu = $(this);
					if (!thisMenu.hasClass("js-mouseleave"))
					{
						thisMenu.addClass("js-mouseleave");
					}
					fromToClass(thisMenu, "is-toggled", "is-not-toggled");
					fromToClass(thisMenu.children("ul"), "is-toggled", "is-not-toggled");
				});
			} 
			else
			{
				fromToClass(thisMenu, "is-toggled", "is-not-toggled");
				fromToClass(thisMenu.children("ul"), "is-toggled", "is-not-toggled");
			}
		}

		// Don't know how to test for event binding -> could be improved
		if (thisMenu.hasClass("js-mouseleave")) 
		{
			thisMenu.removeClass("js-mouseleave");
			thisMenu.unbind("mouseleave");
		}

		switchClass(thisMenu, "is-not-toggled", "is-toggled");
		switchClass(thisMenu.children("ul"), "is-toggled", "is-not-toggled");

		setTimeout( function() {
			isLangHover(thisMenu);
		}, 3000);

	});




 	// Manage theme toggle and update cookie values. ( also manage reCaptcha theme toggle )
 	$(".menu-theme").click( function() {

 		var body = $("body");
 		var allButtons = $(".menu-theme");

 		var c_name = "theme";
 		var c_value;
 		var c_exDate = 30; //Days

 		switchClass(allButtons.children(),"fa-sun-o","fa-moon-o");
 		switchClass(body,"th-dark","th-light");
 		if (body.hasClass('th-dark')) {
 			allButtons.attr("title","toggle day mode");
 			c_value = "th-dark";
	        grecaptcha.reset(widgetId1, {
	            'sitekey' : '6LfS5ggTAAAAAERF8SrqqTaWKt4nYpvh0nCwiEmT',
	            'theme' : 'dark'
	        });
 		}
 		else
 		{
 			allButtons.attr("title","toggle night mode");
 			c_value = "th-light";
	        grecaptcha.reset(widgetId1, {
	            'sitekey' : '6LfS5ggTAAAAAERF8SrqqTaWKt4nYpvh0nCwiEmT',
	            'theme' : 'light'
	        });
 		}

		var exdate=new Date()
		exdate.setDate(exdate.getDate()+c_exDate)
		document.cookie=c_name+ "=" +escape(c_value)+
		((c_exDate==null) ? "" : ";expires="+exdate.toGMTString())

 	});




 	// Set autoGrow of textarea to all textarea tags
	$("textarea").each( function() {
		$(this).autogrow();
	});




	// Manage state of input fields if contains value.
	$("[type='text'], [type='email'], [type='url'], [type='tel'], textarea").focusout( function() {
		var thisInput = $(this);
		if (thisInput.val() != "")
		{
			if(!thisInput.hasClass('has-value'))
			{
				thisInput.addClass('has-value');
			}
		} 
		else 
		{
			if(thisInput.hasClass('has-value'))
			{
				thisInput.removeClass('has-value');
			}
		}
	});




	// Add / Remove fieldsets button trigger
	$("[class*='js-btn-']").click( function() {
		addFieldsets($(this));
	});

	// Email verification field behavior trigger
	$("[id*='-email-verification']").keyup( function() {
		emailVerification($(this));
	});

	// Email validation field behavior trigger
	$("[id*='-email-validator']").keyup( function() {
		emailValidation($(this));
	});




	// "Same as Primary Contact" checkbox behavior
	$("#f-sameas-primary").change( function() {

		// If user CHECK the "same as primary contact" checkbox
	    if($(this).is(':checked'))
	    {
	        // If any of the targeted billing info fields already has data -> Do this
	        if (to_fn.val() != "" || to_ln.val() != "" || to_ph.val() != "" || to_email.val() != "")
	        {
	        	// If primary contact has data -> Do this
	        	if (fr_fn.val() != "" || fr_ln.val() != "" || fr_ph.val() != "" || fr_email.val() != "")
	        	{	
					setDialog("primary");
		        	confirmBoxType = "confirm";
					modalDecision = "sameas";
	        		btnVisibility(confirmBoxType);
		        	overlay();
	        	} 
	        	else
	        	{
					setDialog("noprimary");
	        		confirmBoxType = "alert";
		        	modalDecision = "alert";
	        		btnVisibility(confirmBoxType);
		        	overlay();
		        	$(this).attr('checked', false);
	        	}
	        } 
	        // If all billing info fields are empty -> Do this
	        else 
	        {
        		// If primary contact has data -> Do this
	        	if (fr_fn.val() != "" || fr_ln.val() != "" || fr_ph.val() != "" || fr_email.val() != "") 
	        	{
	        		replaceInfo();
	        	} 
	        	else
	        	{
					setDialog("noprimary");
	        		confirmBoxType = "alert";
		        	modalDecision = "alert";
	        		btnVisibility(confirmBoxType);
		        	overlay();

		        	$(this).attr('checked', false);
	        	}
	        }
	    }
	});




	// If "same as primary" checkbox checked on key up in primary contact section -> copy the information in billing section
	$(".js-fieldset-1").find("[id*='f-contact']").keyup( function() {

		if ($("#f-sameas-primary").is(':checked'))
		{
			// If data in the field is the same as the primary contact and not empty -> remove data
	        for (var i = 0, fl = toFields.length; i < fl; i++)
	        {
	        	if (fromFields[i].val() !== toFields[i].val())
        		{
        			toFields[i].val(fromFields[i].val());

        			if (toFields[i] == to_email)
        			{
        				emailVerification(toFields[i]);
        			}
        			else if (toFields[i] == to_emailValid) 
        			{
        				emailValidation(toFields[i]);
        			}
        		}
	        }
		}
		
	});




	// If billing section on keyup different then Primary -> uncheck the "same as" primary checkbox
	$(".js-f-main-fieldset").find("[id*='f-billing']").keyup( function() {

		if ($("#f-sameas-primary").is(':checked'))
		{
	        for (var i = 0, fl = toFields.length; i < fl; i++)
	        {
	        	if (fromFields[i].val() !== toFields[i].val())
        		{
		        	$("#f-sameas-primary").attr('checked', false);
        		}
	        }
		}
		
	});




	// input type="tel" formatting on keyup behavior
	$("[type='tel']").keyup( function() {

		var thisField = $(this);
		var thisValue = thisField.val();

		if (/^\((\b\d{3}\b)\) (\b\d{4}\b)$/g.test(thisValue))
		{
			thisField.val(thisValue.substring(0,thisValue.length - 1) + "-" + thisValue.charAt(thisValue.length - 1));
		}
		else if (/^\((\b\d{3}\b)\)\d$/g.test(thisValue))
		{
			thisField.val(thisValue.substring(0,thisValue.length - 1) + " " + thisValue.charAt(thisValue.length - 1));
		}
		else if (/^(\b\d{3}\b)$/g.test(thisValue))
		{
			thisField.val("(" + thisValue +")");
		}
	});



	// Manage select tag state if option is selected or not
	$('select').change( function(e) {
		var thisSelect = $(this);
		var option = thisSelect.find('option:selected').val();

		if (option != "") {
			// console.log("i have selection");
			if (!thisSelect.hasClass("has-selection"))
			{
				thisSelect.addClass("has-selection");
			}
		}
		else
		{
			console.log("i have NO selection");
			if (thisSelect.hasClass("has-selection"))
			{
				thisSelect.removeClass("has-selection");
			}
		}

	});




	// Toggle sub-section for radio buttons
	$("[type='radio']").change(function(e) {
		toggleArea($(this));
	});

	// Toggle sub-section for checkbox buttons
	$("[type='checkbox']").change(function(e) {
		toggleArea($(this));
	});



	// Manage input field values if "closed" option selected.
	$("[id*='f-hours']").change( function() {
		var closedCheckbox = $(this).closest("[class*='js-fieldset-']").find(".js-hours-closed");
		var closedCount = $(this).closest("[class*='js-fieldset-']").find(".js-opt-closed:checked").length;

		if (closedCheckbox.is(':checked') && closedCount === 3)
		{
			closedCheckbox.prop('checked', false);
		}
		else if (!closedCheckbox.is(':checked') && closedCount === 4) 
		{
			closedCheckbox.prop('checked', true);
		}

	});




	// Manage more info button block behavior -> Show / Hide content
	$(".m-block-info").hide(); // hide on page load
	$(".js-toggle-info").click( function(e) {

		var theBlock = $(this).parent().parent('.m-inquiry-block');
		var theContainer = theBlock.find(".m-block-info").first();
		var theBlockquote = theContainer.find("blockquote").first();

		e.preventDefault();
		theBlock.toggleClass("toggle-on");

		if (theContainer.is(":visible"))
		{
			if (theBlockquote.hasClass("animate"))
			{
				theBlockquote.removeClass("animate");
			}
			theContainer.slideToggle( 100, "swing" );
		} 
		else
		{
			theContainer.slideToggle( 200, "swing", function() {
				if (!theBlockquote.hasClass("animate"))
				{
					theBlockquote.addClass("animate");
				}
			});
		}
	});




    // Smooth link transition
    // http://www.learningjquery.com/2007/10/improved-animated-scrolling-script-for-same-page-links
    $(function() {
      $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[id=' + this.hash.slice(1) +']');
          var targetId = target.attr("id");
          var targetSelector = $("#"+targetId);
          console.log(targetId);
          if (target.length) {
            $('html,body').animate({
              scrollTop: target.offset().top - 50
            }, 500 , function() {
            	targetSelector.addClass("js-anim-bg");
				setTimeout( function() {
					targetSelector.removeClass("js-anim-bg");
				}, 2000);
            });
            return false;
          }
        }
      });
    });




    // Set tabindex on all input/textarea fields to enable tabs through checkbox
    var tabCount = 1;
    $("input, textarea, select").each( function() {
    	$(this).attr("tabindex", tabCount);
    	tabCount++;
    });
    $("#f-submit").attr("tabindex", tabCount);
    tabCount++;
    $("#f-reset").attr("tabindex", tabCount);
    tabCount++;




	// RESET ALL -> location.reload crolltop
	$("[type='reset']").click( function() {
		var formPos = $("form").offset().top;
		document.body.scrollTop = document.documentElement.scrollTop = formPos;
		document.location.reload();
	});




	// Validation javascript
	// if(grecaptcha.getResponse() == "")
	//     alert("You can't proceed!");
	// else
	//     alert("Thank you");




	 $('#projecklist').validate({
	  // rules: {
		 //   f_contact_firstname_1: {
		 //    minlength: 2,
		 //    maxLength: 100,
		 //    required: true
		 //   },
		 //   f_contact_lastname_1: {
		 //    minlength: 2,
		 //    maxLength: 100,
		 //    required: true
		 // 	 //   f_contact_phone_1: {
		 //    minlength: 2,
		 //    maxLength: 14,
		 //    required: true
		 //   },
		 //   f_contact_email_verification_1: {
		 //    minlength: 2,
		 //    maxLength: 100,
		 //    email: true,
		 //    required: true
		 //   },
		 //   f_contact_email_validator_1: {
		 //    minlength: 2,
		 //    maxLength: 100,
		 //    email: true,
		 //    required: true
		 //   },
		 //   f_condition: {
		 //    minlength: 2,
		 //    required: true
		 //   }
	  // },
	  // highlight: function(element) {
	  //  $(element).closest('.control-group').removeClass('success').addClass('error');
	  // },
	  // success: function(element) {
	  //  element.text('OK!').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
	  // },
	  submitHandler: function(form) {
	   // do other stuff for a valid form
	   $.post('process.php', $("#projecklist").serialize(), function(data) {
	    // $("#projecklist").hide();
	    $('#results').html(data);
	   });
	  }
	 });




	// Set font proper awesome icon for theme selection buttons for the current theme.
	function loadTheme() {
	 	if ($("body").hasClass("th-light")) {
	 		$(".menu-theme").children().addClass("fa-moon-o");
	 	} 
	 	else if ($("body").hasClass("th-dark"))
	 	{
	 		$(".menu-theme").children().addClass("fa-sun-o");
	 	}
	}
	loadTheme();

	// // DEBUG cookie content
	// console.log(document.cookie);




















 	// DEBUG menu button click behavor -> Fill all input/textarea fieldsets
	$("#f-debug-fill-form").click( function() {

		$(".js-btn-show").each( function() {
			$(this).trigger( "click" );
		});


		$($("[type='radio']").get().reverse()).each(function() { 
			$(this).prop('checked', true);
			toggleArea($(this));
		});


		$("select").each( function() {
			var thisSelect = $(this);
			if (!thisSelect.parents("[class*='js-fieldset']").hasClass("is-hidden"))
			{
				/**
				 * Returns a random integer between min (inclusive) and max (inclusive)
				 * Using Math.round() will give you a non-uniform distribution!
				 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/random
				 */
				function getRandomInt(min, max) {
				    return Math.floor(Math.random() * (max - min + 1)) + min;
				}

				var optionCount = $(this).find("option").length;
				var cycle = 3;

				if (thisSelect.parent().hasClass("m-range")) 
				{
					cycle = getRandomInt(3, 7);
				}
				else
				{
					cycle = getRandomInt(2, optionCount - 1);
				}

				thisSelect.find("option:nth-of-type("+ cycle +")").prop('selected', true);	
			
			};

		});


		$("input").each( function() {
			var thisField = $(this);
			var isVisible = !thisField.parents("[class*='js-fieldset']").hasClass("is-hidden");

			switch (thisField.attr("type")) {
				case "checkbox":
					if (isVisible) {
						if (thisField.attr("id") != "f-hours-set-closed-1") {
							thisField.prop('checked', true);
							toggleArea(thisField);
						}
					}
					break;

				case "text":
					if (isVisible) 
					{
						if (/\bpostal\b/ig.test(thisField.attr("id")))
						{
							thisField.val("A0A 0A0");
							thisField.keyup();
						}
						else if (/\bfirstname\b/ig.test(thisField.attr("id")))
						{
							thisField.val("John");
							thisField.keyup();
						}
						else if (/\blastname\b/ig.test(thisField.attr("id")))
						{
							thisField.val("Doe");
							thisField.keyup();
						}
						else if (/\bproject\b/ig.test(thisField.attr("id")))
						{
							thisField.val("XXXX's website");
							thisField.keyup();
						}
						else
						{
							thisField.val("Single line text value here");
							thisField.keyup();
						}
					}
					break;

				case "tel":
					if (isVisible) 
					{
						thisField.val("(514) 575-4414");
						thisField.keyup();
					}
					break;

				case "email":
					if (isVisible) 
					{
						thisField.val("idaniel.racine@gmail.com");
						thisField.keyup();
					}
					break;

				default:
					// console.log("This field is of type: " + thisField.attr("type"));
					break;
			}

		});


		$("textarea").each( function() {
			var thisField = $(this);
			if (!thisField.parents("[class*='js-fieldset']").hasClass("is-hidden")) {
				if (!thisField.hasClass("is-hidden") && $(this).attr("name") !== "g-recaptcha-response") {
					thisField.val("First line od text\nSecond line of text");
					thisField.keyup();
				}
			}
		});


		$("#f-submit").focus();

		// scroll to btn when finished *ios fix
		$("html, body").animate({
			scrollTop: $("#f-submit").offset().top - vh/2
		}, 500);


	});




	// DEBUG -> Toggle Icon Debug Classes
	$(".js-ico-debug").click( function() {

		var thisButton = $(this);
		var c_container = thisButton.parent().find('.m-ico-container');
		var c_wrapper = thisButton.parent().find('.m-ico-wrapper');
		var c_module = thisButton.parent().find('.m-ico-module');
		var c_label = thisButton.parent().find('.m-ico-label');

		c_container.toggleClass('m-ico-debug');
		c_wrapper.toggleClass('m-ico-debug');
		c_module.toggleClass('m-ico-debug');

		c_label.each( function() {
		    $(this).toggleClass('m-ico-debug');
		});

	});






}); // document.ready END




















/*=======================================================================================
	ON SCROLL SCRIPT
=======================================================================================*/

$( window ).scroll(function() {


    scrollPostion = $(document).scrollTop();
    // console.log("POS: "+scrollPostion+"\n");


	// Trigger the theme toggle button onScroll 
	var themePos = ($(".section-action").offset().top)+($(".section-action").height() / 2);
    if (scrollPostion > themePos)
    {
		var themeButton = $(".m-float-radial .menu-theme");
    	if (!themeButton.is(":visible"))
    	{
    		themeButton.show();
    	}
    }
    else
    {
		var themeButton = $(".m-float-radial .menu-theme");
    	if (themeButton.is(":visible"))
    	{
    		themeButton.hide();
    	}
    }


	// Trigger animation of "more info" block when info button is visible in viewport
	if (!$(".js-hint").hasClass("anim-buzz"))
	{
		var hintSel = $(".js-hint");
    	var hintPos = hintSel.offset().top;
	    if (scrollPostion > (hintPos - st) && scrollPostion < (hintPos - nd) )
	    {
		    hintSel.each( function() {
		    	var thisButton = $(this);
				if (verge.inY(thisButton, -animPosition))
				{
					thisButton.addClass("anim-buzz");
				}
		    });
	    }
    }
    
    // on vwTablet and larger, trigger animation of action-section icons in sequence. 
    if (vw >= vwTablet)
    {
		if (!$(".js-action-first").hasClass("anim-lock"))
		{
			var actionPos = $(".js-action-first").offset().top;
	    	if ( scrollPostion > (actionPos - st) && scrollPostion < (actionPos - nd) )
	    	{
				if (verge.inY($(".js-action-first"), -animPosition)) 
				{
					$(".js-action-first").addClass("anim-lock");
					var icoSelectors = $("[class*='js-action']");

					var check = 1;
					var x = 0;
		            function spring( ico ) {

						var thisIco = ico.find(".m-ico-wrapper");

		                switch (check !== 0) {    	
							case (check == 1):
		                    	x = x + 300;
								break;
							case (check == 2):
		                    	x = x + 200;
								break;
							case (check == 3):
		                    	x = x + 100;
								break;
							default:
			                    x = 0;
								break;
		                }

		            	check++;

		                setTimeout( function() {
							if (!thisIco.hasClass("js-in-view"))
							{
							    thisIco.addClass("js-in-view");
							}
		                }, x);

		            }

		            icoSelectors.each( function() {
		            	spring($(this));
		            });
				}
			}
		}
    }
    
    // On mobilish display, trigger animation of action-section icons and action badge in .m-hero as they apear in viewport
    if (vw < vwTablet)
    {
    	if ( scrollPostion < 2000 )
		{

			$(".m-ico-square").each( function() {
				var thisIco = $(this);
				var thisIcoWrapper = thisIco.find(".m-ico-wrapper");
				if (!thisIcoWrapper.hasClass('js-in-view'))
				{
					if (verge.inY(thisIco, -animPosition))
					{
						thisIcoWrapper.addClass('js-in-view');
					}
				}

			});

		}
    }


    // Triger divider CCS3 animation as they come close to view.
    $(".th-divider").each( function() {
    	thisSection = $(this);
    	var sectionOff = thisSection.offset().top - vh - 200;
    	if (scrollPostion > sectionOff && !thisSection.hasClass("animate"))
    	{
    		thisSection.addClass("animate");
    	}
    });


});













