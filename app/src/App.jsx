import {
    Box,
    Button,
    Input,
    Stack,
    Toggle,
    toast,
} from "@uvodohq/planum"
import {useEffect, useState} from "react"
import {OverlayLoader, PageDescription} from "./components"
import http from "./config/http"

function App() {
    const [cardlink_api_key, setCardLinkApiKey] = useState("")

    const [loading, setLoading] = useState(false)
    const [errors, setErrors] = useState(undefined)
    const [message, setMessage] = useState(undefined)

    useEffect(() => {
        setLoading(true)
        http.get("/uvodohq/cardlink/settings").then(
            (res) => {
                setCardLinkApiKey(res.cardlink_api_key)

                setLoading(false)
            },
            (err) => {
                setLoading(false)
                console.log(err)
                setErrors(undefined)
                setMessage("Oops. Something went wrong")
            }
        )
    }, [])

    useEffect(() => {
        if (message && errors !== undefined) {
            toast(message)
        }
    }, [message])

    const handleSave = () => {
        setLoading(true)
        setErrors(undefined)
        setMessage(undefined)

        http
            .post("/uvodohq/cardlink/settings", {
                cardlink_api_key,
            })
            .then(
                (res) => {
                    setLoading(false)
                    toast("Plugin settings successfully updated")
                },
                (err) => {
                    if (err.response.data && err.response.data.errors) {
                        setErrors(err.response.data.errors)
                    }
                    setLoading(false)
                    toast("Oops. Something went wrong")
                }
            )
    }

    function getErrorMessage(selected_field) {
        let message = null

        errors &&
        errors.map((err) => {
            if (err.field === selected_field) {
                message = err.title
            }
        })

        return message
    }

    return (
        <Box
            css={{
                px: "$16",
                "@tablet": {
                    maxWidth: 504,
                    px: 0,
                    py: 2
                },
            }}
        >
            <PageDescription
                title="Cardlink plugin"
                description="Cardlink is payment gateway plugin. It allows you to accept payments from your customers with cardlink."
            />
            <OverlayLoader isLoading={loading} showSpinner>
                <Stack y={24}>
                    <Input
                        aria-label="label"
                        label={"API Key"}
                        placeholder="API Key"
                        value={cardlink_api_key}
                        onChange={setCardLinkApiKey}
                        status={getErrorMessage("cardlink_api_key") ? "error" : "normal"}
                        errorMessage={getErrorMessage("cardlink_api_key")}
                    />

                    <Button
                        onClick={handleSave}
                        isLoading={loading}
                        css={{
                            float: "right",
                        }}
                    >
                        Save changes
                    </Button>
                </Stack>
            </OverlayLoader>
        </Box>
    )
}

export default App
