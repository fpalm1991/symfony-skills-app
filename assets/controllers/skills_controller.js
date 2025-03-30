import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('Skills Controller connecting...');
    }

    get csrfProtectionTokenValue() {
        return this.element.dataset.csrfProtectionTokenValue;
    }

    async selectSkill(e) {
        const checkbox = e.target;
        const selectedSkillId = checkbox.dataset.skillId;
        const alreadyChecked = checkbox.checked;
        const csrfToken = this.csrfProtectionTokenValue;

        const response = await fetch('/my-skills', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken

            },
            body: JSON.stringify({
                skill_id: selectedSkillId,
                checkbox_checked: alreadyChecked,
                _csrf_token: csrfToken,
            })
        })

        if (!response.ok) {
            throw new Error("Response was not ok");
        }

        try {
            const data = await response.json();
            console.log(data);

            const sliderInput = document.getElementById(`skill-level-${selectedSkillId }`)

            if (alreadyChecked) {
                sliderInput.style.display = "block";
            } else {
                sliderInput.style.display = "none";
            }
        } catch (error) {
            console.error("Error selecting skill:", error);
        }
    }

    async updateSkillLevel(e) {
        const slider = e.currentTarget;
        const skillId = slider.dataset.skillId;
        const skillLevel = e.currentTarget.value;
        const csrfToken = this.csrfProtectionTokenValue;

        const response = await fetch(`/my-skills/${skillId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                skill_id: skillId,
                skill_level: skillLevel,
                _csrf_token: csrfToken,
            })
        })

        if (!response.ok) {
            throw new Error("Response was not ok");
        }

        try {
            const data = await response.json();
            console.log(data);
        } catch (error) {
            console.error("Error selecting skill:", error);
        }
    }
}
