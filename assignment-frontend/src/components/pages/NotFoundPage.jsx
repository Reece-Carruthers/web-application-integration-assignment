import notFoundImage from '../../assets/notFoundImage.png'
export default function NotFoundPage() {

    return (
        <>
            <div className="flex justify-center">
                <img rel="DALL·E 2024-01-03 17.18.24 - An image clearly displaying the letters '404',designed in a clean and unobstructed style. The letters '404' should be the main focus of the image"
                    src={notFoundImage}
                    className="min-h-screen">
                </img>
            </div>
        </>
    )
}