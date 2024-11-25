const calendarBody = document.getElementById("calendarBody");
const currentMonth = document.getElementById("currentMonth");
const prevMonth = document.getElementById("prevMonth");
const nextMonth = document.getElementById("nextMonth");

let date = new Date();

// Dummy events array
const events = [
    { date: "2024-11-05", title: "Team Meeting" },
    { date: "2024-11-10", title: "Project Deadline" },
    { date: "2024-11-14", title: "Doctor Appointment" },
    { date: "2024-11-23", title: "Birthday Party" },
    { date: "2024-11-30", title: "Conference" },
];

function renderCalendar() {
    calendarBody.innerHTML = "";
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Adjust to start from Monday (0=Sunday -> 6=Saturday)
    const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;

    // Set current month
    currentMonth.textContent = date.toLocaleDateString("en-US", {
        month: "long",
        year: "numeric",
    });

    // Create blank cells before the first day
    for (let i = 0; i < adjustedFirstDay; i++) {
        const blankCell = document.createElement("td");
        calendarBody.appendChild(blankCell);
    }

    // Create cells for each day
    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement("td");
        cell.textContent = day;

        // Highlight today's date
        const today = new Date();
        if (
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ) {
            cell.classList.add("selected");
        }

        // Check for events on this day
        const eventDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
        const event = events.find((e) => e.date === eventDate);

        if (event) {
            const eventMarker = document.createElement("div");
            eventMarker.classList.add("event-marker");
            eventMarker.title = event.title; // Tooltip with event title
            cell.appendChild(eventMarker);

            // Add click event to show event details
            cell.addEventListener("click", () => {
                alert(`Event: ${event.title}\nDate: ${event.date}`);
            });
        }

        calendarBody.appendChild(cell);
    }
}

function changeMonth(offset) {
    date.setMonth(date.getMonth() + offset);
    renderCalendar();
}

prevMonth.addEventListener("click", () => changeMonth(-1));
nextMonth.addEventListener("click", () => changeMonth(1));

// Initial render
renderCalendar();
