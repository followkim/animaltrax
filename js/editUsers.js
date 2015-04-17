// JQuery for adding a new user

// Pull the relevent elements from the page
var $userName = $("[name='username']");
var $password1 = $("[name='password1']");
var $password2 = $("[name='password2']");

// Create the hint spans
var $p1_hint = $(" <span id='error'></span>");
$p1_hint.insertAfter($password1);
$p1_hint.hide();

var $p2_hint = $(" <span id='error'></span>");
$p2_hint.insertAfter($password2);
$p2_hint.hide();

var $submit_button = $(".submit_button");
$submit_button.attr('disabled', true);

// Are we adding or editing a user?
var isEdit = ($submit_button.val() == "Edit User");

// User names must be at least one character
function checkUsernameLength() {
	return ($userName.val().length != 0);
}

// Passwords must be at least 5 characters
function checkPasswordLength(inPassword) {
	return (inPassword.val().length >= 5);
}

// When entering a new user, the confirm password should match
function checkConfirmPasswordMatch() {
	return ($password1.val() == $password2.val());
}

// Check that we have a valid password (p1)
function checkPassword() {
	if (checkPasswordLength($password1)) {
		$p1_hint.hide();
	} else {
		console.log("show p1");
		$p1_hint.text("Password must be at least 5 characters");
		$p1_hint.show();
	}
}

// Check that the confirm password matches the given passwords
function checkConfirmPassword() {
	if (checkConfirmPasswordMatch()) {
		$p2_hint.hide();
	} else {
		console.log("show p2");
		$p2_hint.text("Passwords don't match!");
		$p2_hint.show();
	}
}

function checkNewPassword() {
	console.log("checkNewPassword");
	if (!checkConfirmPasswordMatch()) {
		$p2_hint.hide();o(3d printer penis
		$p2_hint.text("New password should be different.");
		$p2_hint.show();
		return;
	}

	if (checkPasswordLength($password2)) {
		$p2_hint.hide();
	} else {
		$p2_hint.text("Password must be at least 5 characters");
		$p2_hint.show();
	}
}

function allowSubmitNewUser() {
	$submit_button.attr('disabled', !(checkPasswordLength($password1) && checkConfirmPasswordMatch() && checkUsernameLength()));
}

function allowSubmitEditUser() {
	$submit_button.attr('disabled', !(checkUsernameLength() && checkPasswordLength($password1) && checkPasswordLength($password2)));
}

var checkFunction;
if (isEdit) {
	checkFunction = allowSubmitEditUser;
	p2Function = checkNewPassword;
} else {
	checkFunction = allowSubmitNewUser;
	p2Function = checkConfirmPassword;
}

//When event happens on password input
$userName.focus(checkFunction).keyup(checkFunction);
$password1.focus(checkPassword).keyup(checkPassword).focus(checkFunction).keyup(checkFunction);
$password2.focus(p2Function).keyup(p2Function).focus(checkFunction).keyup(checkFunction);
