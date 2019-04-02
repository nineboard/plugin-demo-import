
# XE3 Demo Import Plugin
이 플러그인은 Xpressengine3(이하 XE3)의 플러그인입니다.

## What can I do?
본 플러그인을 사용하여 DemoImport를 지원하는 Theme의 Demo Site를 추가할 수 있습니다.

# Installation
### Web install
- 관리자 > 플러그인 > 플러그인 추가에서 `DemoImport` 검색 후 설치하기

# Usage
### 사용자
-  관리자 > 플러그인 > 데모 가져오기에서 Demo Site를 추가할 테마를 선택 후 데모 데이터 가져오기 버튼을 클릭합니다.
### 테마 제작자
테마 Component에서 아래와 같은 함수를 구현해서 원하는 메뉴를 추가합니다.

`getThemeConfigInformation` [필수]
- 테마의 설정값을 반환합니다.
- 해당 함수가 구현되어 있어야 DemoImport 플러그인에서 Demo Data를 제공하는 테마로 인식합니다.

`getMenuItemContents`
- 추가할 메뉴의 정보를 반환합니다.

`getBoardContentsInformation`
- 게시판에 게시물을 추가하려고 하는 경우 함수를 구현해서 게시물의 정보를 반환합니다.

`getBannerContentsInformation`
- 배너를 추가하려고 하는 경우 함수를 구현해서 배너 아이템의 정보를 반환합니다.

`getWidgetpageContentsInformation`
- 위젯 페이지를 추가하려고 하는 경우 함수를 구현해서 위젯 아이템의 정보를 반환합니다.

## License
이 플러그인은 LGPL라이선스 하에 있습니다. <https://opensource.org/licenses/LGPL-2.1>
![License](http://img.shields.io/badge/license-GNU%20LGPL-brightgreen.svg)
