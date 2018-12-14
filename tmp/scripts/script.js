var cProgressOpt = {
        line_width: 6,
        color: "#e08833",
        starting_position: 0, // 12.00 o' clock position, 25 stands for 3.00 o'clock (clock-wise)
        percent: 3, // percent starts from
        percentage: true,
        text: "N/A"
    };
$(document).ready(function() {
    $(".my-progress-bar").circularProgress(cProgressOpt);
	$('head').append('<script src="scripts/script2.js"></script>');
});
