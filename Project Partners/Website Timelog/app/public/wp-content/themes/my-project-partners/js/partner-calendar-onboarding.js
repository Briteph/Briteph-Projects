function partnerCalendarOnboarding(){

    const timesheetIllustration = `<svg width="456" height="285" viewBox="0 0 456 285" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M271.135 66.7759C271.135 66.7759 267.236 70.6746 274.477 100.194C281.717 129.713 298.148 152.269 298.148 152.269C298.148 152.269 319.312 134.447 312.907 103.814C306.781 73.1809 271.135 66.7759 271.135 66.7759Z" fill="#161428"/>
        <path d="M275.314 153.662C275.314 153.662 269.744 155.333 256.656 168.978C243.567 182.624 224.352 189.586 201.516 199.89C178.681 210.193 167.542 162.295 173.39 138.067C178.96 113.839 204.023 97.1302 204.023 97.1302L252.757 103.814C252.757 103.814 268.909 84.5985 275.314 97.1302C281.44 109.662 280.884 137.788 275.314 153.662Z" fill="#FFCCB3"/>
        <path d="M233.541 242.497L67.5662 280.649C60.3257 282.32 53.0852 277.864 51.4144 270.624L4.351 66.7757C2.68012 59.5352 7.13575 52.2946 14.3763 50.6237L180.351 12.4718C187.592 10.801 194.832 15.2567 196.503 22.4972L243.288 226.345C245.237 233.586 240.781 240.826 233.541 242.497Z" fill="#F8F8F8"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M192.604 23.3922C191.435 18.3384 186.368 15.1886 181.25 16.3696L15.2756 54.5215C15.2751 54.5216 15.2745 54.5218 15.274 54.5219C10.2186 55.6894 7.0673 60.7571 8.24837 65.8761C8.2484 65.8762 8.24834 65.8759 8.24837 65.8761L55.3117 269.724C56.4786 274.781 61.5471 277.933 66.6667 276.752L66.67 276.751L232.641 238.6C237.713 237.429 240.775 232.4 239.425 227.385L239.406 227.313L192.605 23.3968C192.605 23.3953 192.604 23.3937 192.604 23.3922ZM179.455 8.57374C188.815 6.41554 198.226 12.1746 200.4 21.598L200.401 21.6026L247.171 225.382C249.667 234.823 243.824 244.229 234.44 246.395L234.437 246.396L68.4655 284.547C59.1042 286.707 49.6918 280.948 47.5168 271.524C47.5167 271.524 47.5168 271.524 47.5168 271.524L0.453423 67.6757C-1.7069 58.3143 4.05212 48.9013 13.4767 46.7264L179.451 8.57451C179.453 8.57425 179.454 8.57399 179.455 8.57374Z" fill="#2D2369"/>
        <path opacity="0.3" d="M163.642 70.9528L55.5916 95.7376C51.9714 96.573 48.6295 94.3451 47.794 90.7249C46.9586 87.1046 49.1866 83.7629 52.8068 82.9275L160.857 58.1427C164.478 57.3072 167.819 59.535 168.655 63.1553C169.212 66.4971 166.984 70.1174 163.642 70.9528Z" fill="#6F8699"/>
        <path d="M55.313 124.421L176.174 96.5732" stroke="#6F8699" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M60.0469 144.75L180.908 116.902" stroke="#6F8699" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M143.312 161.738L188.705 151.155" stroke="#6F8699" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M148.325 183.738L193.996 173.434" stroke="#6F8699" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M153.338 206.016L180.908 199.611" stroke="#6F8699" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M69.8088 45.9686L69.8306 45.9642L69.8524 45.9592L128.87 32.3183C134.273 31.2206 136.16 24.8413 132.811 20.4768C132.809 20.4742 132.807 20.4717 132.805 20.4691L131.145 18.2559L131.122 18.2251L131.097 18.1956C129.364 16.1162 126.566 15.0487 123.746 15.7479L67.2268 28.8338L67.2141 28.8367L67.2014 28.8399C64.4361 29.5312 62.3455 31.6217 61.6542 34.387L61.6455 34.4217L61.6385 34.4567L61.0817 37.2415C61.0817 37.2415 61.0817 37.2415 61.0817 37.2416C60.0364 42.4683 64.582 47.0139 69.8088 45.9686Z" fill="#FFCCD8" stroke="#FFCCD8" stroke-width="3"/>
        <path d="M256.654 86.8267C256.654 86.8267 249.692 90.447 236.325 86.8267C222.958 83.2065 220.73 79.8647 206.806 76.2445C192.882 72.6242 173.667 76.5229 170.325 82.371C166.983 88.2191 175.895 91.8394 187.869 93.2318C199.844 94.6242 205.414 117.46 224.629 126.093C256.654 140.295 285.059 96.852 256.654 86.8267Z" fill="#FFCCB3"/>
        <path d="M170.325 82.3711C166.983 88.2192 175.895 91.8395 187.869 93.2319C199.844 94.6243 205.414 117.46 224.629 126.093C231.312 129.156 237.718 129.434 243.566 128.32" stroke="#FF8C52" stroke-opacity="0.4" stroke-width="1.8" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M277.819 74.0161C277.819 74.0161 263.06 83.4845 256.655 86.8263C256.655 86.8263 269.187 99.0795 274.199 121.079C279.212 143.079 275.313 153.662 275.313 153.662C275.313 153.662 291.743 151.991 299.819 146.143C299.819 146.143 307.617 133.333 301.212 108.269C294.807 83.206 277.819 74.0161 277.819 74.0161Z" fill="#F8F8F8"/>
        <path d="M452.705 40.0417C441.566 3.28225 398.123 -11.7558 366.654 10.2442C348.275 23.0544 329.06 35.586 313.743 43.9405C271.135 67.0544 271.135 67.0543 271.135 67.0543C271.135 67.0543 295.363 91.0037 298.984 117.459C302.604 143.915 298.148 152.548 298.148 152.548C298.148 152.548 364.705 136.118 423.465 107.434C448.249 94.9024 460.781 66.4974 452.705 40.0417Z" fill="#6E55FF"/>
        <path d="M292.579 132.219C292.579 130.269 291.187 128.877 289.238 128.877C287.288 128.877 285.896 130.269 285.896 132.219C285.896 134.168 287.288 135.56 289.238 135.56C291.187 135.56 292.579 134.168 292.579 132.219Z" fill="#161428"/>
    </svg>`;

    const partnerCalendarTour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: {
            enabled: true
            },
            classes: 'class-1 class-2',
            modalOverlayOpeningRadius: 8,
            scrollTo: { behavior: 'smooth', block: 'center' }
        },
        useModalOverlay: true
    });
    partnerCalendarTour.addStep({
        text: `${timesheetIllustration}<h3 class="mb-1 mt-2 h4 dark-text">Your weekly timesheet</h3><p class="mt-0 mb-0 grey">Logging your timesheet will help us flag if you are overworked.</p>`,
        buttons: [
            {
            action() {
                return this.next();
            },
            text: 'Get started',
            classes: 'full'
            }
        ],
        classes: 'splash',
        id: 'partner-calendar-splash'
    });
    partnerCalendarTour.addStep({
        title: 'Adding timelogs',
        text: `Click on any timeslot in the calendar add a work session. Add as many as you need.`,
        attachTo: {
            element: '.monday .hour.add-new:nth-child(13)',
            on: 'right'
        },
        buttons: [
            {
            action() {
                return this.back();
            },
            classes: 'shepherd-button-secondary',
            text: 'Back'
            },
            {
            action() {
                return this.next();
            },
            text: 'Next',
            }
        ],
        id: 'adding-timelogs',
        popperOptions: {
                modifiers: [{ name: 'offset', options: { offset: [0, 16] } }]
            }
    });
    partnerCalendarTour.addStep({
        title: 'Completing your timesheet',
        text: `When you are done logging your time, mark your timesheet as "complete" & send your timesheet for approval.`,
        attachTo: {
            element: '#mark-complete',
            on: 'top'
        },
        buttons: [
            {
            action() {
                return this.back();
            },
            classes: 'shepherd-button-secondary',
            text: 'Back'
            },
            {
            action() {
                return this.next();
            },
            text: 'Next',
            }
        ],
        id: 'completing-timesheet',
        popperOptions: {
                modifiers: [{ name: 'offset', options: { offset: [0, 16] } }]
            }
    });

    partnerCalendarTour.addStep({
        title: 'Leave feedback',
        text: `When completing your timesheet, you can leave feedback for each engagement you worked on. Clients won't be able to see this, but it will help us prioritize work you enjoy more.`,
        attachTo: {
            element: '#mark-complete',
            on: 'top'
        },
        buttons: [
            {
            action() {
                return this.back();
            },
            classes: 'shepherd-button-secondary',
            text: 'Back'
            },
            {
            action() {
                return this.next();
            },
            text: 'Start logging time',
            action() {
                // Dismiss the tour when the finish button is clicked
                dismissTour();
                return this.hide();
                }
            }
        ],
        id: 'leaving-timesheet-feedback',
        popperOptions: {
                modifiers: [{ name: 'offset', options: { offset: [0, 16] } }]
            }
    });

    function dismissTour() {
        if (!localStorage.getItem('partner-calendar-tour')) {
            localStorage.setItem('partner-calendar-tour', 'yes');
        }
    }

    // Dismiss the tour when the cancel icon is clicked. Do not show the tour on next page reload
    partnerCalendarTour.on('cancel', dismissTour);

    // Initiate the tour
    if (!localStorage.getItem('partner-calendar-tour')) {
        partnerCalendarTour.start();
    }

}

partnerCalendarOnboarding();