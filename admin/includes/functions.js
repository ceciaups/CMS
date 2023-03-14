function confirmDelete(deleteItem) {
	if (!confirm("Are you sure you want to delete this " + deleteItem + "?"))
		event.preventDefault();
}
