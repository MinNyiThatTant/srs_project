const containerE1 = document.querySelector(".contain");
// Phrases to cycle through
const carer = [
    "Smart.",
    "Technological-University.",
    "Fresh.",
    "Innovation."
];
let carerIndex = 0;
let charIndex = 0;
function updateText() {
    charIndex++;
    const fullPhrase = carer[carerIndex];
    const currentText = fullPhrase.slice(0, charIndex).trim();
    // Check if phrase has multiple words (full name case)
    const isMultiWord = fullPhrase.trim().split(/\s+/).length > 1;

let article = '';
    if (currentText.length > 1 && !isMultiWord) {
        // Show article only if phrase is a single word (like job title)
        const firstChar = currentText.charAt(0).toLowerCase();
        article = 'aeiou'.includes(firstChar) ? 'an ' : 'a ';
    }
    // For multi-word phrase (name), no article.
    containerE1.innerHTML = `<h2>WYTU is ${article}${currentText}</h2>`;
    if (charIndex === fullPhrase.length) {
        carerIndex++;
        charIndex = 0;
    }
    if (carerIndex === carer.length) {
        carerIndex = 0;
    }
    setTimeout(updateText, 300);
}

// start animation
updateText();