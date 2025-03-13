function multiply(){
    const input1 = parseFloat(document.getElementById('input1').value);
    const input2 = parseFloat(document.getElementById('input2').value);

    if(isNaN(input1) || isNaN(input2)){
        document.getElementById('result').textContent = "Enter a valid number !"
    } else {
        const result = input1 * input2;
        document.getElementById('result').textContent = `Result: ${result}`;
    }
}