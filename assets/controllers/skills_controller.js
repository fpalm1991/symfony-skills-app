import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    connect() {
        console.log("Skills Controller connecting...");
    }

    get csrfProtectionTokenValue() {
        return this.element.dataset.csrfProtectionTokenValue;
    }

    async selectSkill(e) {
        const checkbox = e.target;
        const selectedSkillId = checkbox.dataset.skillId;
        const alreadyChecked = checkbox.checked;
        const csrfToken = this.csrfProtectionTokenValue;

        const response = await fetch("/my-skills", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                skill_id: selectedSkillId,
                checkbox_checked: alreadyChecked,
                _csrf_token: csrfToken,
            }),
        });

        if (!response.ok) {
            throw new Error("Response was not ok");
        }

        try {
            // const data = await response.json();
            // console.log(data);

            const skillSliderContainer = document.getElementById(`skill-level-${selectedSkillId}`);

            if (alreadyChecked) {
                skillSliderContainer.style.display = "block";

                // Reset skill level to 1
                console.log("Resetting skill level...");
                const slider = document.getElementById(`skill-slider-${selectedSkillId}`);
                if (slider) {
                    slider.value = 1;
                    await this.updateSkillLevel(null, slider, 1);
                }
            } else {
                skillSliderContainer.style.display = "none";
            }
        } catch (error) {
            console.error("Error selecting skill:", error);
        }
    }

    async updateSkillLevel(e, sliderInputElement = null, level = null) {
        const slider = sliderInputElement ?? e?.currentTarget;
        const newSkillLevel = level !== null ? +level : +slider?.value ?? 1;

        if (!slider) {
            console.warn("No slider element provided or found.");
            return;
        }

        const csrfToken = this.csrfProtectionTokenValue;
        const skillId = slider.dataset.skillId;

        const response = await fetch(`/my-skills/${skillId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                skill_id: skillId,
                skill_level: newSkillLevel,
                _csrf_token: csrfToken,
            }),
        });

        if (!response.ok) {
            throw new Error("Response was not ok");
        }

        try {
            // const data = await response.json();
            // console.log(data);
        } catch (error) {
            console.error("Error selecting skill:", error);
        }
    }
}
