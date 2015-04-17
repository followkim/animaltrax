// When adding an animal, the user is given the option to enter some 
// standard information that is found on the Pixie intake form.
// Depending on if a dog or cat was selected, show the intake info for the 
// correct species.

// Grab the elements from the page
var $dogs = $(".intake_dogs");
var $cats = $(".intake_cats");
var $species_dd = $("[name='species']");

// Hide both at start 
$dogs.hide();
$cats.hide();

// Attach an event handler when the Dropdown is changed
// if not a dog or cat, then hide both
$species_dd.change(function () {
	switch(this.value) {
		case 'C':
			$dogs.hide();
			$cats.show();
			break;
		case 'D':
			$dogs.show();
			$cats.hide();
			break;
		default:
			$dogs.hide();
			$cats.hide();
	}
});

