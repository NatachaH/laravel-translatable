/*
|--------------------------------------------------------------------------
| Translatable - Script
|--------------------------------------------------------------------------
|
| Copyright © 2020 Natacha Herth, design & web development | https://www.natachaherth.ch/
|
*/

var toggles = document.querySelectorAll('.lang-toggle');

toggles.forEach((toggle, i) => {
  // Get the lang
  var lang = toggle.getAttribute('data-lang');
  // On click on .lang-toggle
  toggle.addEventListener('click', (event) => {
      toggleByLang(lang);
      toggleCurrentValue(lang);
  });
});

/**
 * Toggle element with data-lang
 * @param  string lang lang to display
 * @return void
 */
function toggleByLang(lang)
{

    // Get all item with attribute data-lang
    var items = document.querySelectorAll('[data-lang]');

    items.forEach((item, i) => {

        // Is item a link '.lang-toggle'
        var isLink = item.classList.contains('lang-toggle');

        // As the lang to display
        var asLang = item.getAttribute('data-lang') === lang;

        // Is the lang empty
        var langEmpty = lang === '' || lang === null ||  lang === undefined;

        // Display
        if(isLink)
        {
            // If link add/remove class active
            asLang ? item.classList.add('active') : item.classList.remove('active');
        } else {

            // If not link add/remove class d-none
            asLang || langEmpty ? item.classList.remove('d-none') : item.classList.add('d-none');
        }

    });

}

/**
 * Toggle the current lang value
 * @param  string lang
 * @return void
 */
function toggleCurrentValue(lang)
{
    var currents = document.querySelectorAll('.lang-toggle-current');
    currents.forEach((item, i) => {
      item.innerHTML = lang;
    });
}
