$(function () {
    //fancyfileuplod
    $("#demo").FancyFileUpload({
        params: {
            action: "fileuploader",
        },
        maxfilesize: 2000000,
    });
});