document.addEventListener("DOMContentLoaded", function () {
    function showCalendar() {
        document.getElementById('list-view').classList.add('hidden');
        document.getElementById('calendar-view').classList.remove('hidden');
        document.getElementById('calendar-tab').classList.add('bg-black', 'text-white');
        document.getElementById('calendar-tab').classList.remove('bg-gray-200');
        document.getElementById('list-tab').classList.remove('bg-black', 'text-white');
        document.getElementById('list-tab').classList.add('bg-gray-200');
    }

    function showList() {
        document.getElementById('list-view').classList.remove('hidden');
        document.getElementById('calendar-view').classList.add('hidden');
        document.getElementById('list-tab').classList.add('bg-black', 'text-white');
        document.getElementById('list-tab').classList.remove('bg-gray-200');
        document.getElementById('calendar-tab').classList.remove('bg-black', 'text-white');
        document.getElementById('calendar-tab').classList.add('bg-gray-200');
    }

    function openPopup() {
        document.getElementById('popup').classList.remove('hidden');
    }

    function closePopup() {
        document.getElementById('popup').classList.add('hidden');
    }

    // Add event listeners
    document.getElementById('calendar-tab').addEventListener('click', showCalendar);
    document.getElementById('list-tab').addEventListener('click', showList);
    
    // Make functions globally available
    window.openPopup = openPopup;
    window.closePopup = closePopup;
});
