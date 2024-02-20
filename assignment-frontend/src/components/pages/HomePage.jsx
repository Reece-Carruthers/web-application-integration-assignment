import PreviewVideo from "../pageComponents/PreviewVideo.jsx"
import {useFetchData} from "../../hooks/useFetchData.jsx"
import {useFetchDataByContentID} from "../../hooks/useFetchDataByContentID.jsx"
import PreviewContent from "../pageComponents/PreviewContent.jsx"
import {useMemo} from "react"
import Message from "../pageComponents/Message.jsx";

/**
 * Homepage of the website, displays a random preview video and the abstract of the preview
 * @returns {JSX.Element}
 * @generated Used ChatGPT for refactoring
 * @author Reece Carruthers (W19011575)
 */
export default function HomePage() {
    const previewParams = useMemo(() => ({'limit': 1}), [])
    const {
        data: previewData,
        isLoading: isPreviewLoading,
        isError: isPreviewError
    } = useFetchData('https://w19011575.nuwebspace.co.uk/assignment/api/preview/', previewParams)

    const {
        additionalData,
        isAdditionalLoading,
        isAdditionalError
    } = useFetchDataByContentID('https://w19011575.nuwebspace.co.uk/assignment/api/content/', previewData)


    return (
        <>
            <div rel="Image by rawpixel.com on Freepik (http://www.freepik.com)"
                 className="bg-[url('/src/assets/background.jpg')] min-h-screen">
                <div className="overflow-auto pl-2 pr-2 pb-4">
                    {isPreviewLoading && <Message message={"Loading preview..."}/>}
                    {isPreviewError && <Message message={"There was an error loading the preview video, please try again later"} />}
                    {!isPreviewError && previewData && previewData.data && previewData.data.map(preview => (
                        <PreviewVideo key={preview.content_id} id={preview.content_id} title={preview.title}
                                      previewLink={preview.preview_video}
                        />
                    ))}
                    <div>
                        {isAdditionalLoading && <p>Loading preview abstract...</p>}
                        {isAdditionalError && <p>There was an error loading preview abstract</p>}
                        {!isAdditionalError && additionalData && additionalData.data && additionalData.data.map(additional => {
                            {
                                return (<div key={additional.content_id}>
                                    {additional.content_abstract &&
                                        <PreviewContent abstract={additional.content_abstract}/>
                                    }
                                </div>)
                            }
                        })}
                    </div>
                </div>
            </div>
        </>
    )
}