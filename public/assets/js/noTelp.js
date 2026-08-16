const operators = [
    {
        "name": "kartuHalo",
        "operator": "Telkomsel",
        "code": [11]
    },
    {
        "name": "simPATI",
        "operator": "Telkomsel",
        "code": [12,13,21]
    },
    {
        "name": "LOOP",
        "operator": "Telkomsel",
        "code": [22]
    },
    {
        "name": "KARTU As",
        "operator": "Telkomsel",
        "code": [21,23,52,53]
    },
    {
        "name": "by.U / Kartu As",
        "operator": "Telkomsel",
        "code": [51]
    },
    {
        "name": "IndosatM2",
        "operator": "Indosat Ooredoo",
        "code": [14]
    },
    {
        "name": "Matrix",
        "operator": "Indosat Ooredoo",
        "code": [55]
    },
    {
        "name": "Mentari",
        "operator": "Indosat Ooredoo",
        "code": [58]
    },
    {
        "name": "Mentari/Matrix",
        "operator": "Indosat Ooredoo",
        "code": [15,16]
    },
    {
        "name": "IM3",
        "operator": "Indosat Ooredoo",
        "code": [56,57]
    },
    {
        "name": "XL",
        "operator": "XL Axiata",
        "code": [17,18,19,59,77,78,79]
    },
    {
        "name": "Axis",
        "operator": "XL Axiata",
        "code": [31,32,33,38]
    },
    {
        "name": "3",
        "operator": "3",
        "code": [95,96,97,98,99],
        "validationConfig": {
            "maxLength" : 13
        }
    },
    {
        "name": "Smartfren",
        "operator": "Smartfren",
        "code": [81,82,83,84,85,86,87,88,89]
    },
    {
        "name": "Net1",
        "operator": "Net1",
        "code": [27, 28]
    },
    {
        "name": "ByRU",
        "operator": "ByRU",
        "code": [68]
    },
];
 
const ValidationMessage = {
    "VALID" : "VALID",
    "INVALID": "INVALID",
    "BELOW_MIN" : "BELOW MINIMUM LENGTH",
    "ABOVE_MAX" : "ABOVE MAXIMUM LENGTH",
    "NOT_FOUND" : "NOT FOUND"
}

const validationConfig = {
    "minLength": 10,
    "maxLength": 12
}

const numericOnly = (value) => {
    return value.replace(/\D/g,'');
} 
 
const getOperator = (no, validate = false) => {
    let code;
    let result = {
        valid: false,
        message: ValidationMessage.INVALID
    }

    // Null Validation
    if(!no){
        return result;
    }

    // + prepend
    if(no.substr(0,1) === '+'){
        no = no.substr(1);
    }

    // country code
    if(no.substr(0,2) === '62'){
        no = '0'+no.substr(2);
    }

    // convert to numeric only
    no = numericOnly(no);

    // get code
    if(no.substr(0,2) === '08'){
        code = no.substr(2,2);
    }else {
        return result;
    }

    const found = operators.some(data => {
        if(data.code.some (item => item == code)){
            result.operator = data.operator;
            result.card = data.name;
            result.message = ValidationMessage.VALID;
            result.valid = true;

            if(validate){

                // get validationConfig from data
                if(data.validationConfig && data.validationConfig.minLength){
                    validationConfig.minLength = data.validationConfig.minLength;
                }

                if(data.validationConfig && data.validationConfig.maxLength){
                    validationConfig.maxLength = data.validationConfig.maxLength;
                }

                // check validation
                if(no.length < validationConfig.minLength){
                    result.valid = false;
                    result.message = ValidationMessage.BELOW_MIN;
                }else if(no.length > validationConfig.maxLength){
                    result.valid = false;
                    result.message = ValidationMessage.ABOVE_MAX;
                }
            }
            return true;
        }
    });

    if(!found){
        result.valid = false;
        result.message = ValidationMessage.NOT_FOUND;
    }

    return result;
}




