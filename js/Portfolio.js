const projects = $(".project-description");
const projectImages = $(".project-image");
// const currentProject = projects.filter(".show");
const hamburgerMenu = $("#header-dropdown-menu");
const projectDescriptionArrowBtns = $(
	"#projects-description-arrows"
).children();
const prevProjectBtn = $("#projects-description-prev-btn");
const nextProjectBtn = $("#projects-description-next-btn");

function findPrevProject(currentProject) {
	return findAdjacentProjects(true, currentProject);
}

function findNextProject(currentProject) {
	return findAdjacentProjects(false, currentProject);
}

function findAdjacentProjects(isPrev, currentProject) {
	const currenetProjectNum = Number(currentProject.attr("data-project-num"));
	let targetProjectNum = isPrev
		? currenetProjectNum - 1
		: currenetProjectNum + 1;
	if (targetProjectNum < 0 || targetProjectNum >= projects.length) return null;
	return projects.filter(function (index) {
		return $(projects[index]).attr("data-project-num") == targetProjectNum;
	});
}

for (let i = 0; i < projects.length; ++i) {
	$(projects[i]).attr("data-project-num", i);
}
$(projects[0]).addClass("show");
$("#current-project-number").text(1);
$("#total-project-number").text(projects.length);

$(projectImages[0]).addClass("show");

hamburgerMenu.click(function () {
	hamburgerMenu.toggleClass("close");
});

projectDescriptionArrowBtns.click(async function () {
	const currentProject = projects.filter(".show");
	const currentProjectImage = projectImages.filter(".show");
	let projectToShow =
		$(this).attr("id") === "projects-description-prev-btn"
			? findPrevProject(currentProject)
			: findNextProject(currentProject);
	if (projectToShow == null) return;
	projectDescriptionArrowBtns.css("pointer-events", "none");
	currentProject.removeClass("show");
	currentProjectImage.removeClass("show");
	await new Promise((resolve) => setTimeout(resolve, 1000));
	$("#current-project-number").text(
		Number(projectToShow.attr("data-project-num")) + 1
	);
	projectToShow.addClass("show");
	$(projectImages[Number(projectToShow.attr("data-project-num"))]).addClass(
		"show"
	);
	projectDescriptionArrowBtns.css("pointer-events", "auto");
});
