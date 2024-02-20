import {useEffect, useMemo, useState} from "react"
import {useFetchData} from "../../hooks/useFetchData.jsx"
import Pagination from "../pageComponents/Pagination.jsx"
import Select from "../pageComponents/Select.jsx"
import ContentItem from "../pageComponents/ContentItem.jsx"
import Message from "../pageComponents/Message.jsx";

/**
 * A page to display content about the conference
 * @returns {JSX.Element}
 *
 * @generated - Had ChatGPT help with setting an item to localStorage and general refactoring
 * @author Reece Carruthers (W19011575)
 */
export default function ContentPage() {
    const [currentPage, setCurrentPage] = useState(() => {
        const storedPage = localStorage.getItem('currentPage')
        const pageNumber = Number(storedPage)
        return !isNaN(pageNumber) && pageNumber > 0 ? pageNumber : 1
    })
    const [selectedContent, setSelectedContent] = useState(null)
    const [currentSelection, setCurrentSelection] = useState("")

    const contentParams = useMemo(() => ({
        'page': currentPage,
        ...(currentSelection && {'type': currentSelection})
    }), [currentPage, currentSelection])

    const listTypesParams = useMemo(() => ({'type': 'listTypes'}), [])

    useEffect(() => {
        localStorage.setItem('currentPage', JSON.stringify(currentPage))
    }, [currentPage])

    const setSelection = (value) => {
        setCurrentSelection(value)
        setCurrentPage(1)
    }

    const setPage = (value) => {
        setCurrentPage(value)
    }

    const toggleAuthorAffiliation = (contentID) => {
        setSelectedContent(prev => prev === contentID ? null : contentID)
    }

    const { data: contentData, isLoading: isContentLoading, isError: isContentError } =
        useFetchData('https://w19011575.nuwebspace.co.uk/assignment/api/content/', contentParams)

    const { data: contentTypeData, isError: isContentTypesError } =
        useFetchData('https://w19011575.nuwebspace.co.uk/assignment/api/content/', listTypesParams)

    const contentTypes = contentTypeData?.data?.map(contentType => contentType.name) || []

    const hasContent = contentData?.data?.length > 0
    const showNoContentMessage = () => !isContentError && contentData && !hasContent
    const showContent = () => !isContentError && !isContentLoading && hasContent
    const showSelect = () => !isContentError && !isContentLoading && !isContentTypesError && contentTypeData && contentTypeData.data.length > 0

    return (
        <div className="rounded p-1 bg-[url('/src/assets/background.jpg')] min-h-screen bg-cover bg-no-repeat">
            {isContentLoading && <Message message={"Loading conference content..."}/>}
            {isContentError && <Message message={"There was an error fetching conference content, please try again later..."}/>}
            {showContent() && contentData.pagination.pages > 1 && <Pagination currentPage={currentPage} totalPages={contentData.pagination.pages} setPage={setPage} />}
            {showSelect() && <Select selectOptions={contentTypes} defaultOption="All content" setSelection={setSelection}/>}
            {showNoContentMessage() && <Message message={"There was no content with the selected type"}/>}
            {showContent() && <ContentDisplay contentData={contentData} selectedContent={selectedContent} toggleAuthorAffiliation={toggleAuthorAffiliation} />}
            {showContent() && contentData.pagination.pages > 1 && <Pagination currentPage={currentPage} totalPages={contentData.pagination.pages} setPage={setPage} />}
        </div>
    )
}

const ContentDisplay = ({ contentData, selectedContent, toggleAuthorAffiliation }) => (
    <>
        <div className="grid auto-rows-auto grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-5 mb-10">
            {contentData.data.map(content => <ContentItem key={content.content_id} content={content} isSelected={selectedContent === content.content_id} toggleAuthorDetails={toggleAuthorAffiliation} />)}
        </div>
    </>
)