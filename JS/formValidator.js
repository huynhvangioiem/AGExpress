//Languages javascript
function Validator(options) {

  var selectorRules = {};
  //Lay elements cua form can validate
  var formElement = document.querySelector(options.form);
  if (formElement) {
    // Khi submit
    formElement.onsubmit = function (e) {
      e.preventDefault();
      var isFormValid = true;

      options.rules.forEach(rule => {
        var inputElement = formElement.querySelector(rule.selector);
        var isValid = validate(inputElement, rule);
        if (!isValid) {
          isFormValid = false;
        }
      });

      if (isFormValid) {
        //submit form with js
        if (typeof options.onSubmit === 'function') {
          var enableInputs = formElement.querySelectorAll('[name]');
          var formValues = Array.from(enableInputs).reduce((values, input) => {
            switch (input.type) {
              case 'radio':
                values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
                break;
              case 'checkbox':
                if (input.matches(':checked')) {
                  if(!Array.isArray(values[input.name])) values[input.name] = [input.value];
                  else values[input.name].push(input.value);
                }
                break;
              case 'file':
                values[input.name] = input.files;
                break;
              default:
                values[input.name] = input.value;
            }
            return values;
          }, {});
          options.onSubmit(formValues);
        } else {
          formElement.submit();
        }
        // default
      }

    }

    //Lap qua moi rule va xu ly su kien
    options.rules.forEach(rule => {
      // Lưu lại các rules cho mỗi input
      if (Array.isArray(selectorRules[rule.selector])) {
        selectorRules[rule.selector].push(rule.test);
      } else {
        selectorRules[rule.selector] = [rule.test];
      }

      var inputElements = formElement.querySelectorAll(rule.selector);
      Array.from(inputElements).forEach(inputElement => {
        //Xu ly truong hop blur khoi input
        inputElement.onblur = function () {
          validate(inputElement, rule);
        }

        //Xu ly khi nguoi dung nhap vao input
        inputElement.oninput = function () {
          var errorElement = inputElement.parentElement.querySelector(options.errorSelector);
          errorElement.innerText = '';
          inputElement.parentElement.classList.remove('invalid');
        }
      })

    });
  }

  //Ham thuc thi validate
  function validate(inputElement, rule) {
    var errorElement = inputElement.parentElement.querySelector(options.errorSelector);
    var errorMessage;

    //Lay ra cac rules cua selector
    var rules = selectorRules[rule.selector];

    //Lap qua tung rule kiem tra, neu co loi thi dung kiem tra
    for (let i = 0; i < rules.length; i++) {
      switch (inputElement.type) {
        case 'radio':
        case 'checkbox':
          errorMessage = rules[i](
            formElement.querySelector(rule.selector + ':checked')
          );
          break;
        default: errorMessage = rules[i](inputElement.value);
      }
      if (errorMessage) break;
    }
    if (errorMessage) {
      errorElement.innerText = errorMessage;
      inputElement.parentElement.classList.add('invalid');
    } else {
      errorElement.innerText = '';
      inputElement.parentElement.classList.remove('invalid');
    }
    return !errorMessage;
  }
}


// Định nghĩa rules
// Nguyên tắc của các rules:
// 1. Khi có lỗi => Trả ra message lỗi
// 2. Khi hợp lệ => Không trả ra cái gì cả (undefined)
Validator.isRequired = function (selector, message) {
  return {
    selector: selector,
    test: function (value) {
      return value ? undefined : message || 'Vui lòng nhập trường này!'
    }
  };
}

Validator.isEmail = function (selector, message) {
  return {
    selector: selector,
    test: function (value) {
      var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      return regex.test(value) ? undefined : message || 'Trường này phải là email';
    }
  };
}

Validator.isConfirmed = function (selector, confirmValues, message) {
  return {
    selector: selector,
    test: function (value) {
      return value === confirmValues() ? undefined : message || 'Giá trị nhập vào không chính xác';
    }
  }
}

Validator.isSelected = function (selector, message) {
  return {
    selector: selector,
    test: function (value) {
      return value !== "-1" ? undefined : message || 'Vui lòng chọn trường này!';
    }
  }
}

Validator.isTel = function (selector, message) { //vietName telephone number
  return {
    selector: selector,
    test: function (value) {
      var regex = /(0[3|5|7|8|9])+([0-9]{8})\b/;
      return regex.test(value) ? undefined : message || 'Số điện thoại không hợp lệ.';
    }
  };
}

Validator.isPassword = function (selector, message) { //vietName telephone number
  return {
    selector: selector,
    test: function (value) {
      var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,}$/;
      return regex.test(value) ? undefined : message || 'Mật khẩu có độ dài từ 8 ký tự trong đó bao gồm chữ cái viết thường, chữ viết hoa, chữ số và ký tự đặc biệt.';
    }
  };
}

Validator.maxLength = function (selector, max, message) {
  return {
      selector: selector,
      test: function (value) {
          return value.length < max ? undefined : message || `Số ký tự tối đa cho phép là ${max} ký tự`;
      }
  };
}


