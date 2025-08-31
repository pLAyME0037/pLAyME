<style>
.collapsible-content {
    display: none; /* Hidden by default */
    padding-left: 20px; /* Indent list items */
}
.collapsible-header {
    cursor: pointer; /* Indicate it's clickable */
    user-select: none; /* Prevent text selection on click */
    margin-top: 15px; /* Add some space above headers */
    margin-bottom: 5px; /* Add some space below headers */
}
.collapsible-header:hover {
    color: #007bff; /* Highlight on hover */
}
.collapsible-content.active {
    display: block; /* Show when active */
}
</style>

<h3 class="collapsible-header">WU Project Pages</h3>
<ul class="collapsible-content">
    <li><a href="/wu_project/box-shadow">Box Shadow</a></li>
    <li><a href="/wu_project/div">Div Example</a></li>
    <li><a href="/wu_project/form">Form</a></li>
    <li><a href="/wu_project/lesson">Lesson</a></li>
    <li><a href="/wu_project/nav">Navigation</a></li>
    <li><a href="/wu_project/picture">Picture</a></li>
    <li><a href="/wu_project/reading">Reading</a></li>
    <li><a href="/wu_project/shopping">Shopping</a></li>
    <li><a href="/wu_project/video">Video</a></li>
    <li><a href="/wu_project/story/story">Story Index</a></li>
    <li><a href="/wu_project/story/ch1">Story Chapter 1</a></li>
    <li><a href="/wu_project/story/ch2">Story Chapter 2</a></li>
    <li><a href="/wu_project/story/ch3">Story Chapter 3</a></li>
    <li><a href="/wu_project/story/ch4">Story Chapter 4</a></li>
    <li><a href="/wu_project/story/ch5">Story Chapter 5</a></li>
    <li><a href="/wu_project/story/ch6">Story Chapter 6</a></li>
    <li><a href="/wu_project/story/ch7">Story Chapter 7</a></li>
    <li><a href="/wu_project/story/ch8">Story Chapter 8</a></li>
</ul>

<h3 class="collapsible-header">Some Random Lesson</h3>
<ul class="collapsible-content">
    <li><a href="/lesson/DSA">DSA Learning Hub</a></li>
    <li><a href="/lesson/css_Usage">css - usage</a></li>
    <li><a href="/lesson/html_project_sc">html - project - sc</a></li>
</ul>

<h3 class="collapsible-header">Linux</h3>
<ul class="collapsible-content">
    <li><a href="/linuxRelated/grub_problem_in_duelBoot">Grub Problem in DuelBoot</a></li>
</ul>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('.collapsible-header');

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling; // Get the next sibling element (the ul)
            if (content && content.classList.contains('collapsible-content')) {
                content.classList.toggle('active');
            }
        });
    });
});
</script>
