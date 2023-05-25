<script>
import languageColors from "../../data/language-colors.json";

function padZero(str, len) {
    len = len || 2;
    var zeros = new Array(len).join('0');
    return (zeros + str).slice(-len);
}

function invertColor(hex, bw) {
    // https://stackoverflow.com/questions/63378749/is-there-a-way-to-generate-foreground-text-and-navigation-contrasting-colour-t
    if (hex.indexOf('#') === 0) {
        hex = hex.slice(1);
    }
    // convert 3-digit hex to 6-digits.
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (hex.length !== 6) {
        throw new Error('Invalid HEX color.');
    }
    var r = parseInt(hex.slice(0, 2), 16),
        g = parseInt(hex.slice(2, 4), 16),
        b = parseInt(hex.slice(4, 6), 16);
    if (bw) {
        // http://stackoverflow.com/a/3943023/112731
        return (r * 0.299 + g * 0.587 + b * 0.114) > 186
            ? '#000000'
            : '#FFFFFF';
    }
    // invert color components
    r = (255 - r).toString(16);
    g = (255 - g).toString(16);
    b = (255 - b).toString(16);
    // pad each with zeros and return
    return "#" + padZero(r) + padZero(g) + padZero(b);
}

export function generateProjectFrameworkColors() {
    var projectsContainer = document.getElementById("projects-container");
    if (typeof (projectsContainer) != 'undefined' && projectsContainer != null) {
        var languageBubbles = document.getElementsByClassName("framework-bubble");
        for (var i = 0; i < languageBubbles.length; i++) {
            var framework = languageBubbles.item(i).dataset.framework
            var frameworkBgColor = languageColors[framework]
            var textColor = invertColor(frameworkBgColor, true)

            languageBubbles.item(i).style.backgroundColor = frameworkBgColor
            languageBubbles.item(i).style.color = textColor
        }
    }
}
</script>