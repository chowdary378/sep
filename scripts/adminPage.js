
// Function to show the add form

function showAddForm() {
    var addForm = document.getElementById("addForm");
    addForm.style.display = "block";
    var student_info = document.getElementById("student_info");
    student_info.style.display = "none";
}

document.addEventListener('DOMContentLoaded', function() {
    var copyTexts = document.querySelectorAll('.copy-text');
    copyTexts.forEach(function(copyText) {
        copyText.addEventListener('click', function(event) {
            var textToCopy = this.innerText || this.textContent;
            var tempInput = document.createElement('input');
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            
            // Remove any existing tooltip
            var existingTooltip = document.querySelector('.copied-tooltip');
            if (existingTooltip) {
                existingTooltip.parentNode.removeChild(existingTooltip);
            }
            
            // Create and position the tooltip
            var tooltip = document.createElement('span');
            tooltip.classList.add('copied-tooltip');
            tooltip.innerText = 'Copied';
            tooltip.style.position = 'absolute';
            tooltip.style.top = (event.clientY - 20) + 'px'; // 20px above the pointer
            tooltip.style.left = (event.clientX + 10) + 'px'; // 10px to the right of the pointer
            tooltip.style.backgroundColor = '#333';
            tooltip.style.color = '#fff';
            tooltip.style.padding = '5px';
            tooltip.style.borderRadius = '5px';
            tooltip.style.zIndex = '9999'; // Ensure tooltip appears on top of other elements
            document.body.appendChild(tooltip);
            
            // Remove the tooltip after 1 second
            setTimeout(function() {
                document.body.removeChild(tooltip);
            }, 500);
        });
    });
});
