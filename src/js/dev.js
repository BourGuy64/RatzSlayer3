export function readFormData(formData) {
    console.log("Read form data ==>");
    for(let data of formData.entries()) {
        console.log("key: " + data[0] + ', value: ' + data[1]);
    }
    console.log("");
}
