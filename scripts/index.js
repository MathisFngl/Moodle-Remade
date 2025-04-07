function generateDynamicCalendar() {
    const calendar = document.getElementById("calendar");
    const dateElement = document.getElementById("current-date");

    const today = new Date();
    const currentYear = today.getFullYear();
    const currentMonth = today.getMonth();

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const options = { month: 'long', year: 'numeric' };
    dateElement.textContent = today.toLocaleDateString("fr-FR", options);

    const weekdays = ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"];

    let headerRow = document.createElement("div");
    headerRow.className = "calendar-header-row";
    weekdays.forEach(day => {
        let weekdayElement = document.createElement("div");
        weekdayElement.className = "calendar-day weekday";
        weekdayElement.textContent = day;
        headerRow.appendChild(weekdayElement);
    });
    calendar.appendChild(headerRow);

    let dayCounter = 1;

    for (let i = 0; i < 6; i++) {
        let row = document.createElement("div");
        row.className = "calendar-row";

        for (let j = 0; j < 7; j++) {
            let dayElement = document.createElement("div");
            dayElement.className = "calendar-day";

            if (i === 0 && j < firstDay) {
                dayElement.classList.add("empty");
            } else if (dayCounter <= daysInMonth) {
                dayElement.textContent = dayCounter;
                if (dayCounter === today.getDate()) {
                    dayElement.classList.add("today");
                }
                dayCounter++;
            } else {
                dayElement.classList.add("empty");
            }

            row.appendChild(dayElement);
        }
        calendar.appendChild(row);
    }
}

window.onload = function() {
    generateDynamicCalendar();
};
